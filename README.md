# Laravel Repository Generator

**Complete Repository Generator**

This package generates Repositories, Contracts, Resources, Requests, Policies, Controllers, Models and Language Files.
Also implements the Repository Pattern.


## What is a repository?
Use a repository to separate the logic that retrieves the data and maps it to the entity model from the business logic that acts on the model.
The business logic should be agnostic to the type of data that comprises the data source layer. (…)
The repository mediates between the data source layer and the business layers of the application.
It queries the data source for the data, maps the data from the data source to a business entity and
persists changes in the business entity to the data source. A repository separates the business logic
from the interactions with the underlying data source or Web service. — mdsn.microsoft.com


## Installation

Require this package with composer using the following command:

```bash
composer require otodev/laravel-repo-generator
```

Export config using the following command:

```bash
php artisan vendor:publish --tag=repo-generator-config
```

This package makes use of Laravel's package auto-discovery mechanism.



## Usage

- [`php artisan make:api:all Client\Vimeo\Vimeo App\Models\Client\Vimeo\Vimeo` - API generation for Laravel Reposiotry ](#api-generation-repository)


