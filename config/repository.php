<?php
	/*
	|--------------------------------------------------------------------------
	| Otodev Repository Config
	|--------------------------------------------------------------------------
	|
	*/
	
	return [
		
		/*
		|--------------------------------------------------------------------------
		| Core
		|--------------------------------------------------------------------------
		|
		*/
		'core'              => \Otodev\Core\Core::class,
		
		/*
		|--------------------------------------------------------------------------
		| Repository Pagination Limit Default
		|--------------------------------------------------------------------------
		|
		*/
		'pagination'        => [
			'limit'     => 10,
			'max_limit' => 100,
		],
		
		/*
		|--------------------------------------------------------------------------
		| CDN Images Assets Folder
		|--------------------------------------------------------------------------
		|
		*/
		'cdn_images_folder' => env('CDN_IMAGES_FOLDER', 'assets'),
		
		/*
		|--------------------------------------------------------------------------
		| Cache Config
		|--------------------------------------------------------------------------
		|
		*/
		'cache'             => [
			/*
			 |--------------------------------------------------------------------------
			 | Cache Status
			 |--------------------------------------------------------------------------
			 |
			 | Enable or disable cache
			 |
			 */
			'enabled'    => false,
			
			/*
			 |--------------------------------------------------------------------------
			 | Cache Seconds
			 |--------------------------------------------------------------------------
			 |
			 | Time of expiration cache
			 |
			 */
			'minutes'    => 3600,
			
			/*
			 |--------------------------------------------------------------------------
			 | Cache Repository
			 |--------------------------------------------------------------------------
			 |
			 | Instance of Illuminate\Contracts\Cache\Repository
			 |
			 */
			'repository' => 'cache',
			
			/*
			  |--------------------------------------------------------------------------
			  | Cache Clean Listener
			  |--------------------------------------------------------------------------
			  |
			  |
			  |
			  */
			'clean'      => [
				
				/*
				  |--------------------------------------------------------------------------
				  | Enable clear cache on repository changes
				  |--------------------------------------------------------------------------
				  |
				  */
				'enabled' => true,
				
				/*
				  |--------------------------------------------------------------------------
				  | Actions in Repository
				  |--------------------------------------------------------------------------
				  |
				  | create : Clear Cache on create Entry in repository
				  | update : Clear Cache on update Entry in repository
				  | delete : Clear Cache on delete Entry in repository
				  |
				  */
				'on'      => [
					'create' => true,
					'update' => true,
					'delete' => true,
				]
			],
			
			'params'  => [
				/*
				|--------------------------------------------------------------------------
				| Skip Cache Params
				|--------------------------------------------------------------------------
				|
				|
				| Ex: http://otodev.local/?search=lorem&skipCache=true
				|
				*/
				'skipCache' => 'skipCache'
			],
			
			/*
		   |--------------------------------------------------------------------------
		   | Methods Allowed
		   |--------------------------------------------------------------------------
		   |
		   | cache methods : all, paginate, find, findByField, findWhere, getByCriteria
		   |
		   | Ex:
		   |
		   | 'only'  =>['all','paginate'],
		   |
		   | or
		   |
		   | 'except'  =>['find'],
		   */
			'allowed' => [
				'only'   => null,
				'except' => null
			]
		],
		
		/*
		|--------------------------------------------------------------------------
		| Resource Config
		|--------------------------------------------------------------------------
		|
		| Settings of request parameters names that will be used by Resource
		|
		*/
		'resource'          => [
			
			'params' => [
				/*
				|--------------------------------------------------------------------------
				| Skip Resource Params
				|--------------------------------------------------------------------------
				|
				|
				| Ex: http://otodev.local/?search=lorem&skipResource=true
				|
				*/
				'skipResource' => 'skipResource'
			],
		],
		
		/*
		|--------------------------------------------------------------------------
		| Criteria Config
		|--------------------------------------------------------------------------
		|
		| Settings of request parameters names that will be used by Criteria
		|
		*/
		'criteria'          => [
			/*
			|--------------------------------------------------------------------------
			| Accepted Conditions
			|--------------------------------------------------------------------------
			|
			| Conditions accepted in consultations where the Criteria
			|
			| Ex:
			|
			| 'acceptedConditions'=>['=','like']
			|
			| $query->where('foo','=','bar')
			| $query->where('foo','like','bar')
			|
			*/
			'acceptedConditions' => [
				'=',
				'like'
			],
			/*
			|--------------------------------------------------------------------------
			| Request Params
			|--------------------------------------------------------------------------
			|
			| Request parameters that will be used to filter the query in the repository
			|
			| Params :
			|
			| - search : Searched value
			|   Ex: http://otodev.local/?search=lorem
			|
			| - searchFields : Fields in which research should be carried out
			|   Ex:
			|    http://otodev.local/?search=lorem&searchFields=name;email
			|    http://otodev.local/?search=lorem&searchFields=name:like;email
			|    http://otodev.local/?search=lorem&searchFields=name:like
			|
			| - filter : Fields that must be returned to the response object
			|   Ex:
			|   http://otodev.local/?search=lorem&filter=id,name
			|
			| - orderBy : Order By
			|   Ex:
			|   http://otodev.local/?search=lorem&orderBy=id
			|
			| - sortedBy : Sort
			|   Ex:
			|   http://otodev.local/?search=lorem&orderBy=id&sortedBy=asc
			|   http://otodev.local/?search=lorem&orderBy=id&sortedBy=desc
			|
			| - searchJoin: Specifies the search method (AND / OR), by default the
			|               application searches each parameter with OR
			|   EX:
			|   http://otodev.local/?search=lorem&searchJoin=and
			|   http://otodev.local/?search=lorem&searchJoin=or
			|
			*/
			'params'             => [
				'columns'      => 'fields',
				'limit'        => 'limit',
				'page'         => 'page',
				'search'       => 'search',
				'searchFields' => 'searchFields',
				'filter'       => 'filter',
				'sort'         => 'sort',
				'order'        => 'order',
				'orderBy'      => 'orderBy',
				'sortedBy'     => 'sortedBy',
				'with'         => 'with',
				'searchJoin'   => 'searchJoin',
				'withCount'    => 'withCount',
			]
		],
		/*
		|--------------------------------------------------------------------------
		| Generator Config
		|--------------------------------------------------------------------------
		|
		*/
		'generator'         => [
			'basePath'          => app()->path(),
			'rootNamespace'     => 'App\\',
			'stubsOverridePath' => app()->path(),
			'paths'             => [
				'models'       => 'Models',
				'repositories' => 'Repositories\\Eloquent',
				'interfaces'   => 'Contracts\\Repositories',
				'transformers' => 'Transformers',
				'presenters'   => 'Presenters',
				'validators'   => 'Validators',
				'controllers'  => 'Http/Controllers',
				'provider'     => 'RepositoryServiceProvider',
				'criteria'     => 'Criteria'
			]
		]
	];
