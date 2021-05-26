<?php

    namespace Otodev\Contracts;

    use Otodev\Exceptions\RepositoryException;
    use Closure;
    use Eloquent;
    use Illuminate\Contracts\Container\BindingResolutionException;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;

    /**
     * Interface RepositoryContract
     * @package Otodev\Contracts
     */
    interface RepositoryContract {
        /**
         * Trigger static method calls to the model.
         *
         * @param $method
         * @param $arguments
         *
         * @return mixed
         */
        public static function __callStatic($method, $arguments);

        /**
         * Skip Resource.
         *
         * @param bool $status
         *
         * @return $this
         */
        public function skipResource($status = true);

        /**
         * Check if the transforming resource is skipped.
         *
         * @return bool
         */
        public function isSkippedResource();

        /**
         * Save a new entity in repository.
         *
         * @param array $attributes
         * @param bool  $transform Transforms the request data before create the resource
         *
         * @return Eloquent|mixed
         */
        public function create(array $attributes, $transform = false);

        /**
         * Update a entity in repository by id.
         *
         * @param array $attributes
         * @param int   $id        The resource id
         * @param bool  $transform Transforms the request data before update the resource
         *
         * @return Eloquent|Eloquent[]|\Illuminate\Database\Eloquent\Collection|Model|mixed
         */
        public function update(array $attributes, $id, $transform = false);

        /**
         * Update or Create an entity in repository.
         *
         * @param array $attributes
         * @param array $values
         *
         * @return Eloquent|Model|mixed
         */
        public function updateOrCreate(array $attributes, array $values = []);

        /**
         * Delete a entity in repository by id.
         *
         * @param int  $id
         * @param bool $transform Transforms the request data before delete the resource
         *
         * @return int
         */
        public function delete($id, $transform = false);

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
        public function firstWhere($column, $operator = null, $value = null, $boolean = 'and');

        /**
         * Determine if any rows exist for the current query.
         *
         * @param $id
         *
         * @return bool|mixed
         */
        public function exists($id);

        /**
         * Count results of repository.
         *
         * @param array  $where
         * @param string $columns
         *
         * @return int
         */
        public function count(array $where = [], $columns = '*');

        /**
         * Upload image(s) to storage/cdn and save filename(s) to model/database.
         *
         * @param array      $images
         * @param Model|null $model
         *
         * @return void
         */
        public function upload(array $images, Model $model);

        /**
         * Retrieve data array for populate field select.
         *
         * @param string      $column
         * @param string|null $key
         *
         * @return Collection|array
         */
        public function lists($column, $key = null);

        /**
         * Retrieve data array for populate field select.
         *
         * @param string      $column
         * @param string|null $key
         *
         * @return Collection|array
         */
        public function pluck($column, $key = null);

        /**
         * Sync relations.
         *
         * @param      $id
         * @param      $relation
         * @param      $attributes
         * @param bool $detaching
         *
         * @return mixed
         */
        public function sync($id, $relation, $attributes, $detaching = true);

        /**
         * SyncWithoutDetaching.
         *
         * @param $id
         * @param $relation
         * @param $attributes
         *
         * @return mixed
         */
        public function syncWithoutDetaching($id, $relation, $attributes);

        /**
         * Retrieve all data of repository.
         *
         * @param array $columns
         *
         * @return Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function all($columns = ['*']);

        /**
         * Retrieve all data of repository, paginated.
         *
         * @param null  $limit
         * @param array $columns
         *
         * @return mixed
         */
        public function paginate($limit = null, $columns = ['*']);

        /**
         * Retrieve all data of repository, simple paginated.
         *
         * @param null  $limit
         * @param array $columns
         *
         * @return mixed
         */
        public function simplePaginate($limit = null, $columns = ['*']);

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
        public function find($id, $columns = ['*']);

        /**
         * Find data by field and value.
         *
         * @param       $field
         * @param       $value
         * @param array $columns
         *
         * @return mixed
         */
        public function findByField($field, $value, $columns = ['*']);

        /**
         * Find data by multiple fields.
         *
         * @param array $where
         * @param array $columns
         *
         * @return mixed
         */
        public function findWhere(array $where, $columns = ['*']);

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
        public function findWhereIn($field, array $values, $columns = ['*']);

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
        public function findWhereNotIn($field, array $values, $columns = ['*']);

        /**
         * Find data by between values in one field.
         *
         * @param       $field
         * @param array $values
         * @param array $columns
         *
         * @return mixed
         */
        public function findWhereBetween($field, array $values, $columns = ['*']);

        /**
         * Order collection by a given column.
         *
         * @param string $column
         * @param string $direction
         *
         * @return $this
         */
        public function orderBy($column, $direction = 'asc');

        /**
         * Load relations.
         *
         * @param $relations
         *
         * @return $this
         */
        public function with($relations);

        /**
         * Load relation with closure.
         *
         * @param string  $relation
         * @param closure $closure
         *
         * @return $this
         */
        public function whereHas($relation, $closure);

        /**
         * Add sub select queries to count the relations.
         *
         * @param mixed $relations
         *
         * @return $this
         */
        public function withCount($relations);

        /**
         * Set hidden fields.
         *
         * @param array $fields
         *
         * @return $this
         */
        public function hidden(array $fields);

        /**
         * Set visible fields.
         *
         * @param array $fields
         *
         * @return $this
         */
        public function visible(array $fields);

        /**
         * Query Scope.
         *
         * @param Closure $scope
         *
         * @return $this
         */
        public function scopeQuery(Closure $scope);

        /**
         * Reset Query Scope.
         *
         * @return $this
         */
        public function resetScope();

        /**
         * Get Searchable Fields.
         *
         * @return array
         */
        public function getFieldsSearchable();

        /**
         * Retrieve first data of repository, or return new Entity.
         *
         * @param array $attributes
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function firstOrNew(array $attributes = []);

        /**
         * Retrieve first data of repository, or create new Entity.
         *
         * @param array $attributes
         *
         * @return mixed
         * @throws BindingResolutionException
         * @throws RepositoryException
         */
        public function firstOrCreate(array $attributes = []);

        /**
         * Trigger method calls to the model.
         *
         * @param string $method
         * @param array  $arguments
         *
         * @return mixed
         */
        public function __call($method, $arguments);
    }
