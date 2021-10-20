<?php
	
	namespace Otodev\Contracts;
	
	use Closure;
	use Eloquent;
	use Illuminate\Contracts\Container\BindingResolutionException;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Collection;
	use Otodev\Exceptions\RepositoryException;
	
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
		 * Skip Criteria.
		 *
		 * @param bool $status
		 *
		 * @return $this
		 */
		public function skipCriteria(bool $status = true);
		
		/**
		 * Skip Resource.
		 *
		 * @param bool $status
		 *
		 * @return $this
		 */
		public function skipResource(bool $status = true);
		
		/**
		 * Check if the transforming resource is skipped.
		 *
		 * @return bool
		 */
		public function isSkippedResource(): bool;
		
		/**
		 * Save a new entity in repository.
		 *
		 * @param array $attributes
		 *
		 * @return Eloquent|mixed
		 */
		public function create(array $attributes);
		
		/**
		 * Update a entity in repository by id.
		 *
		 * @param array $attributes
		 * @param int   $id The resource id
		 *
		 * @return Eloquent|Eloquent[]|\Illuminate\Database\Eloquent\Collection|Model|mixed
		 */
		public function update(array $attributes, $id);
		
		/**
		 * Update or Create an entity in repository.
		 *
		 * @param array $attributes
		 * @param array $values
		 * @param null  $action
		 *
		 * @return Eloquent|Model|mixed
		 */
		public function updateOrCreate(array $attributes, array $values = [], $action = null);
		
		/**
		 * Delete a entity in repository by id.
		 *
		 * @param int $id
		 *
		 * @return int
		 */
		public function delete($id);
		
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
		public function firstWhere($column, $operator = null, $value = null, string $boolean = 'and');
		
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
		public function count(array $where = [], $columns = '*'): int;
		
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
		public function sync($id, $relation, $attributes, bool $detaching = true);
		
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
		 * @param string[] $columns
		 *
		 * @return Builder[]|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
		 * @throws BindingResolutionException
		 * @throws RepositoryException
		 */
		public function all($columns = ['*']);
		
		/**
		 * Retrieve all data of repository, paginated.
		 *
		 * @param null     $limit
		 * @param string[] $columns
		 *
		 * @return mixed
		 */
		public function paginate($limit = null, $columns = ['*'], string $method = 'paginate');
		
		/**
		 * Retrieve all data of repository, simple paginated.
		 *
		 * @param null     $limit
		 * @param string[] $columns
		 *
		 * @return mixed
		 */
		public function simplePaginate($limit = null, $columns = ['*']);
		
		/**
		 * Find data by id.
		 *
		 * @param          $id
		 * @param string[] $columns
		 *
		 * @return mixed
		 * @throws BindingResolutionException
		 * @throws RepositoryException
		 */
		public function find($id, $columns = ['*']);
		
		/**
		 * Find data by field and value.
		 *
		 * @param          $field
		 * @param          $value
		 * @param string[] $columns
		 *
		 * @return mixed
		 */
		public function findByField($field, $value, $columns = ['*']);
		
		/**
		 * Find data by multiple fields.
		 *
		 * @param array    $where
		 * @param string[] $columns
		 *
		 * @return mixed
		 */
		public function findWhere(array $where, $columns = ['*']);
		
		/**
		 * Find data by multiple values in one field.
		 *
		 * @param          $field
		 * @param array    $values
		 * @param string[] $columns
		 *
		 * @return mixed
		 * @throws BindingResolutionException
		 * @throws RepositoryException
		 */
		public function findWhereIn($field, array $values, $columns = ['*']);
		
		/**
		 * Find data by excluding multiple values in one field.
		 *
		 * @param          $field
		 * @param array    $values
		 * @param string[] $columns
		 *
		 * @return mixed
		 * @throws BindingResolutionException
		 * @throws RepositoryException
		 */
		public function findWhereNotIn($field, array $values, $columns = ['*']);
		
		/**
		 * Find data by between values in one field.
		 *
		 * @param          $field
		 * @param array    $values
		 * @param string[] $columns
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
		public function orderBy(string $column, string $direction = 'asc');
		
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
		public function whereHas(string $relation, Closure $closure);
		
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
		public function __call(string $method, array $arguments);
	}
