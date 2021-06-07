<?php

    namespace Otodev\Eloquent;

    use Otodev\Contracts\CriteriaContract;
    use Otodev\Contracts\RepositoryContract;
    use Otodev\Contracts\RepositoryCriteriaContract;
    use Otodev\Contracts\ValidatorContract;
    use Otodev\Events\RepositoryEntityCreated;
    use Otodev\Events\RepositoryEntityDeleted;
    use Otodev\Events\RepositoryEntityUpdated;
    use Otodev\Exceptions\RepositoryException;
    use Otodev\Exceptions\ValidatorException;
    use Otodev\Validator\RepositoryValidator;
    use Closure;
    use Eloquent;
    use Exception;
    use Illuminate\Container\Container as Application;
    use Illuminate\Contracts\Container\BindingResolutionException;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Throwable;

    /**
     * Class BaseRepository
     * @package Otodev\Eloquent
     */
    abstract class BaseRepository implements RepositoryContract, RepositoryCriteriaContract {
        /**
         * @var Application
         */
        protected $app;

        /**
         * @var Eloquent
         */
        protected $model;

        /**
         * @var array
         */
        protected $fieldSearchable = [];

        /**
         * @var RepositoryValidator
         */
        protected $validator;

        /**
         * Validation Rules
         *
         * @var array
         */
        protected $rules = null;

        /**
         * Collection of Criteria.
         *
         * @var Collection
         */
        protected $criteria;

        /**
         * @var bool
         */
        protected $skipCriteria = false;

        /**
         * @var boolean
         */
        protected $skipResource = false;

        /**
         * @var Closure
         */
        protected $scopeQuery = null;

        /**
         * BaseRepository constructor.
         *
         * @param Application $app
         *
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function __construct(Application $app) {
            $this->app      = $app;
            $this->criteria = new Collection();
            $this->makeModel();
            $this->makeValidator();
            $this->boot();
        }

        /**
         * Trigger static method calls to the model.
         *
         * @param $method
         * @param $arguments
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public static function __callStatic($method, $arguments) {
            return call_user_func_array([new static, $method], $arguments);
        }

        /**
         * Skip transforming resource.
         *
         * @param bool $status
         *
         * @return $this
         */
        public function skipResource($status = true) {
            $this->skipResource = $status;

            return $this;
        }

        /**
         * Check if the transforming resource is skipped.
         *
         * @return bool
         */
        public function isSkippedResource() {
            $skipped = isset($this->skipResource) ? $this->skipResource : false;
            $param   = config('repository.resource.params.skipResource', 'skipResource');

            return request()->boolean($param, $skipped);
        }

        /**
         * Boot up the repository, pushing criteria.
         */
        public function boot() {
        }

        /***
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function resetModel() {
            $this->makeModel();
        }

        /**
         * Specify Model class name.
         *
         * @return string
         */
        abstract public function model();

        /**
         * @return Model|mixed
         * @throws RepositoryException
         * @throws BindingResolutionException
         */
        public function makeModel() {
            $model = $this->app->make($this->model());

            if(!$model instanceof Model) {
                throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
            }

            return $this->model = $model;
        }

        /**
         * Initialize Validator.
         *
         * @throws BindingResolutionException
         */
        public function makeValidator() {
            $this->validator = $this->app->make(ValidatorContract::class, ['rules' => $this->rules]);
        }

        /**
         * Get Searchable Fields
         *
         * @return array
         */
        public function getFieldsSearchable() {
            return $this->fieldSearchable;
        }

        /**
         * Query Scope.
         *
         * @param Closure $scope
         *
         * @return $this
         */
        public function scopeQuery(Closure $scope) {
            $this->scopeQuery = $scope;

            return $this;
        }

        /**
         * Find a model by its primary key with attributes.
         *
         * @param int $id
         *
         * @return bool|mixed
         */
        public function show($id) {
            return false;
        }

        /**
         * Determine if any rows exist for the current query.
         *
         * @param $id
         *
         * @return bool|mixed
         */
        public function exists($id) {
            return $this->model->whereKey($id)->exists();
        }

        /**
         * Retrieve data array for populate field select.
         *
         * @param string      $column
         * @param null|string $key
         *
         * @return array|Collection
         */
        public function lists($column, $key = null) {
            $this->applyCriteria();

            return $this->model->lists($column, $key);
        }

        /**
         * Retrieve data array for populate field select.
         *
         * @param string      $column
         * @param string|null $key
         *
         * @return Collection|array
         */
        public function pluck($column, $key = null) {
            $this->applyCriteria();

            return $this->model->pluck($column, $key);
        }

        /**
         * Sync relations.
         *
         * @param      $id
         * @param      $relation
         * @param      $attributes
         * @param bool $detaching
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function sync($id, $relation, $attributes, $detaching = true) {
            return $this->find($id)->{$relation}()->sync($attributes, $detaching);
        }

        /**
         * SyncWithoutDetaching.
         *
         * @param $id
         * @param $relation
         * @param $attributes
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function syncWithoutDetaching($id, $relation, $attributes) {
            return $this->sync($id, $relation, $attributes, false);
        }

        /**
         * Retrieve all data of repository.
         *
         * @param array $columns
         *
         * @return Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function all($columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();

            if($this->model instanceof Builder) {
                $results = $this->model->get($columns);
            } else {
                $results = $this->model->all($columns);
            }

            $this->resetModel();
            $this->resetScope();

            return $results;
        }

        /**
         * Count results of repository.
         *
         * @param array  $where
         * @param string $columns
         *
         * @return mixed|int
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function count(array $where = [], $columns = '*') {
            $this->applyCriteria();
            $this->applyScope();

            if($where) {
                $this->applyConditions($where);
            }

            $result = $this->model->count($columns);

            $this->resetModel();
            $this->resetScope();

            return $result;
        }

        /**
         * Alias of All method.
         *
         * @param array $columns
         *
         * @return Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function get($columns = ['*']) {
            return $this->all($columns);
        }

        /**
         * Retrieve first data of repository.
         *
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function first($columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();

            $results = $this->model->first($columns);

            $this->resetModel();

            return $results;
        }

        /**
         * Retrieve first data of repository, or return new Entity.
         *
         * @param array $attributes
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function firstOrNew(array $attributes = []) {
            $this->applyCriteria();
            $this->applyScope();

            $model = $this->model->firstOrNew($attributes);

            $this->resetModel();

            return $model;
        }

        /**
         * Retrieve first data of repository, or create new Entity.
         *
         * @param array $attributes
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function firstOrCreate(array $attributes = []) {
            $this->applyCriteria();
            $this->applyScope();

            $model = $this->model->firstOrCreate($attributes);
            $this->resetModel();

            return $model;
        }

        /**
         * Set the "limit" value of the query.
         *
         * @param $limit
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function limit($limit) {
            $this->applyCriteria();
            $this->applyScope();
            $results = $this->model->limit($limit);

            $this->resetModel();

            return $results;
        }

        /**
         * Retrieve all data of repository, paginated.
         *
         * @param null   $limit
         * @param array  $columns
         * @param string $method
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function paginate($limit = null, $columns = ['*'], $method = "paginate") {
            $this->applyCriteria();
            $this->applyScope();

            // Respects max limit
            $limit        = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
            $requestLimit = request('limit');
            if(is_numeric($requestLimit)) {
                $max_limit = config('repository.pagination.max_limit', 20);
                $limit     = ($requestLimit <= $max_limit) ? $requestLimit : $max_limit;
            }

            $results = $this->model->{$method}($limit, $columns);
            $results->appends(app('request')->query());
            $this->resetModel();

            return $results;
        }

        /**
         * Retrieve all data of repository, simple paginated.
         *
         * @param null  $limit
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function simplePaginate($limit = null, $columns = ['*']) {
            return $this->paginate($limit, $columns, "simplePaginate");
        }

        /**
         * Add a basic where clause to the query, and return the first result.
         *
         * @param array|Closure|string $column
         * @param null                 $operator
         * @param null                 $value
         * @param string               $boolean
         *
         * @return RepositoryContract|Model|mixed|null
         */
        public function firstWhere($column, $operator = null, $value = null, $boolean = 'and') {
            return $this->model->where($column, $operator, $value, $boolean)->first();
        }

        /**
         * Find data by id.
         *
         * @param       $id
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function find($id, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();
            //$model = $this->model->findOrFail($id, $columns);
            $model = $this->model->find($id, $columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Find data by field and value.
         *
         * @param       $field
         * @param null  $value
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function findByField($field, $value = null, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->where($field, '=', $value)->get($columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Find data by multiple fields.
         *
         * @param array $where
         * @param array $columns
         *
         * @return Eloquent[]|\Illuminate\Database\Eloquent\Collection|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function findWhere(array $where, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();

            $this->applyConditions($where);

            $model = $this->model->get($columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Find data by multiple values in one field.
         *
         * @param       $field
         * @param array $values
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function findWhereIn($field, array $values, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->whereIn($field, $values)->get($columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Find data by excluding multiple values in one field.
         *
         * @param       $field
         * @param array $values
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function findWhereNotIn($field, array $values, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->whereNotIn($field, $values)->get($columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Find data by between values in one field.
         *
         * @param       $field
         * @param array $values
         * @param array $columns
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function findWhereBetween($field, array $values, $columns = ['*']) {
            $this->applyCriteria();
            $this->applyScope();
            $model = $this->model->whereBetween($field, $values)->get($columns);
            $this->resetModel();

            return $model;
        }

        /**
         * Save a new entity in repository.
         *
         * @param array $attributes
         * @param bool  $transform
         *
         * @return Eloquent|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         * @throws ValidatorException
         */
        public function create(array $attributes, $transform = false) {
            if(!is_null($this->validator)) {
                $attributes = $this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
                $this->validator->with($attributes)->passesOrFail();
            }
	
			$model = tap($this->model->newInstance($attributes), function($instance) use ($attributes) {
				$instance->fill($attributes)->save();
			});
	
			$this->resetModel();

            event(new RepositoryEntityCreated($this, $model));

            return $model;
        }

        /**
         * Update a entity in repository by id.
         *
         * @param array $attributes
         * @param int   $id
         * @param bool  $transform
         *
         * @return Eloquent|Eloquent[]|\Illuminate\Database\Eloquent\Collection|Model|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         * @throws ValidatorException
         */
        public function update(array $attributes, $id, $transform = false) {
            $this->applyScope();

            if(!is_null($this->validator)) {
                $attributes = $this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
                $this->validator->with($attributes)->passesOrFail(ValidatorContract::RULE_UPDATE);
            }
	
			$model = tap($this->model->findOrFail($id), function($instance) use ($attributes) {
				$instance->fill($attributes)->save();
			});
	
			$this->resetModel();

            event(new RepositoryEntityUpdated($this, $model));

            return $model;
        }

        /**
         * Update or Create an entity in repository.
         *
         * @param array $attributes
         * @param array $values
		 * @param bool  $transform
		 * @param null  $action
         *
         * @return Eloquent|Model|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         * @throws ValidatorException
         */
        public function updateOrCreate(array $attributes, array $values = [], $transform = false, $action = null) {
            $this->applyScope();

            if(!is_null($this->validator)) {
                $this->validator->with(array_merge($attributes, $values))->passesOrFail($action);
            }
	
			$model = tap($this->model->firstOrNew($attributes), function($instance) use ($values) {
				$instance->fill($values)->save();
			});
	
			$this->resetModel();

            event(new RepositoryEntityUpdated($this, $model));

            return $model;
        }

        /**
         * Delete a entity in repository by id.
         *
         * @param int  $id
         * @param bool $transform
         *
         * @return int|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function delete($id, $transform = false) {
            $exists = $this->exists($id);
            if(!$exists) return $exists;

            $this->applyScope();

            $model         = $this->find($id);
            $originalModel = clone $model;

            $this->resetModel();

            $deleted = $model->delete();

            event(new RepositoryEntityDeleted($this, $originalModel));

            return $deleted;
        }

        /**
         * Upload image(s) to storage/cdn and save filename(s) to model/database.
         *
         * @param array      $images
         * @param Model|null $model
         *
         * @return void
         */
        public function upload(array $images, ?Model $model = null) {
            try {
                if(!empty($images)) {
                    if(is_null($model)) {
                        $path = config('extras.cdn_images_folder') . '/' . date('Y') . '/' . date('m');
                        collect(request()->allFiles())->flatten()->map(function(UploadedFile $file) use ($path) {
                            $filename        = Str::orderedUuid()->toString() . '-' . now()->getPreciseTimestamp() . '.' . $file->getClientOriginalExtension();
                            $stored_filename = Storage::disk()->putFileAs($path, $file, $filename, ['visibility' => 'public']);
                            if(is_string($stored_filename)) {
                                //
                            }
                        });
                    } else {
                        $path = config('extras.cdn_images_folder') . '/' . Str::snake($model->getTable());
                        collect($images)->map(function($image, $key) use ($path, $model) {
                            if(request()->hasFile($key)) {
                                $file            = request()->file($key);
                                $name            = $model->name ?? $model->title ?? Str::orderedUuid()->toString();
                                $filename        = $name . '-' . now()->getPreciseTimestamp(1) . '.' . $file->getClientOriginalExtension();
                                $stored_filename = Storage::disk()->putFileAs($path, $file, $filename, ['visibility' => 'public']);

                                // Update the model
                                if(is_string($stored_filename)) {
                                    $model->update([$key => $stored_filename]);
                                }
                            }
                        });
                    }
                }
            } catch(Throwable $exception) {
                Log::error("[Throwable Exception] :: Failed update image!", ['Message' => $exception->getMessage(), 'Code' => $exception->getCode()]);
            }
        }

        /**
         * Delete multiple entities by given criteria.
         *
         * @param array $where
         *
         * @return bool|null
         * @throws BindingResolutionException
         * @throws RepositoryException
         * @throws Exception
         */
        public function deleteWhere(array $where) {
            $this->applyScope();
            $this->applyConditions($where);

            $deleted = $this->model->delete();

            event(new RepositoryEntityDeleted($this, $this->model->getModel()));

            $this->resetModel();

            return $deleted;
        }

        /**
         * Check if entity has relation.
         *
         * @param string $relation
         *
         * @return $this
         */
        public function has($relation) {
            $this->model = $this->model->has($relation);

            return $this;
        }

        /**
         * Load relations.
         *
         * @param array|string $relations
         *
         * @return $this
         */
        public function with($relations) {
            $this->model = $this->model->with($relations);

            return $this;
        }

        /**
         * Add sub select queries to count the relations.
         *
         * @param mixed $relations
         *
         * @return $this
         */
        public function withCount($relations) {
            $this->model = $this->model->withCount($relations);

            return $this;
        }

        /**
         * Load relation with closure.
         *
         * @param string  $relation
         * @param closure $closure
         *
         * @return $this
         */
        public function whereHas($relation, $closure) {
            $this->model = $this->model->whereHas($relation, $closure);

            return $this;
        }

        /**
         * Set hidden fields.
         *
         * @param array $fields
         *
         * @return $this
         */
        public function hidden(array $fields) {
            $this->model->setHidden($fields);

            return $this;
        }

        public function orderBy($column, $direction = 'asc') {
            $this->model = $this->model->orderBy($column, $direction);

            return $this;
        }

        /**
         * Set visible fields.
         *
         * @param array $fields
         *
         * @return $this
         */
        public function visible(array $fields) {
            $this->model->setVisible($fields);

            return $this;
        }

        /**
         * Push Criteria for filter the query.
         *
         * @param $criteria
         *
         * @return $this
         * @throws RepositoryException
         */
        public function pushCriteria($criteria) {
            if(is_string($criteria)) {
                $criteria = new $criteria;
            }
            if(!$criteria instanceof CriteriaContract) {
                throw new RepositoryException("Class " . get_class($criteria) . " must be an instance of App\\Package\\Repository\\Contracts\\CriteriaContract");
            }
            $this->criteria->push($criteria);

            return $this;
        }

        /**
         * Pop Criteria.
         *
         * @param $criteria
         *
         * @return $this
         */
        public function popCriteria($criteria) {
            $this->criteria = $this->criteria->reject(function($item) use ($criteria) {
                if(is_object($item) && is_string($criteria)) {
                    return get_class($item) === $criteria;
                }

                if(is_string($item) && is_object($criteria)) {
                    return $item === get_class($criteria);
                }

                return get_class($item) === get_class($criteria);
            });

            return $this;
        }

        /**
         * Get Collection of Criteria.
         *
         * @return Collection
         */
        public function getCriteria() {
            return $this->criteria;
        }

        /**
         * Find data by Criteria.
         *
         * @param CriteriaContract $criteria
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function getByCriteria(CriteriaContract $criteria) {
            $this->model = $criteria->apply($this->model, $this);
            $results     = $this->model->get();
            $this->resetModel();

            return $results;
        }

        /**
         * Skip Criteria.
         *
         * @param bool $status
         *
         * @return $this
         */
        public function skipCriteria($status = true) {
            $this->skipCriteria = $status;

            return $this;
        }

        /**
         * Reset all Criteria.
         *
         * @return $this
         */
        public function resetCriteria() {
            $this->criteria = new Collection();

            return $this;
        }

        /**
         * Reset Query Scope.
         *
         * @return $this
         */
        public function resetScope() {
            $this->scopeQuery = null;

            return $this;
        }

        /**
         * Trigger method calls to the model.
         *
         * @param string $method
         * @param array  $arguments
         *
         * @return mixed
         */
        public function __call($method, $arguments) {
            $this->applyCriteria();
            $this->applyScope();

            return call_user_func_array([$this->model, $method], $arguments);
        }

        /**
         * Apply scope in current Query.
         *
         * @return $this
         */
        protected function applyScope() {
            if(isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
                $callback    = $this->scopeQuery;
                $this->model = $callback($this->model);
            }

            return $this;
        }

        /**
         * Apply criteria in current Query.
         *
         * @return $this
         */
        protected function applyCriteria() {
            if($this->skipCriteria === true) {
                return $this;
            }

            $criteria = $this->getCriteria();

            if($criteria) {
                foreach($criteria as $c) {
                    if($c instanceof CriteriaContract) {
                        $this->model = $c->apply($this->model, $this);
                    }
                }
            }

            return $this;
        }

        /**
         * Applies the given where conditions to the model.
         *
         * @param array $where
         *
         * @return void
         */
        protected function applyConditions(array $where) {
            foreach($where as $field => $value) {
                if(is_array($value)) {
                    list($field, $condition, $val) = $value;
                    $this->model = $this->model->where($field, $condition, $val);
                } else {
                    $this->model = $this->model->where($field, '=', $value);
                }
            }
        }
    }
