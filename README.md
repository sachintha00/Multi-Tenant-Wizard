# Easy2Dev Laravel Tenant Migrate

Easy2Dev Laravel Tenant Migrate is a Laravel package designed to simplify database migration for multi-tenant applications, particularly those employing PostgreSQL databases. 

This package streamlines the migration process for Software as a Service (SaaS) for Laravel web applications where tenant data is stored in separate databases. It eliminates the need to manually adjust database configuration files for each tenant, offering a scalable solution for database management.


## Features
-   Seamless migration of tenant databases without manual           configuration adjustments.
-   Support for multiple migration commands tailored for tenant databases.
-   Simultaneous migration of multiple tenant databases using database prefixes.
-   Compatibility with Laravel artisan commands for streamlined workflow integration.

## Installation
To install Easy2Dev Laravel Tenant Migrate, follow these steps:

1.  Install the package via Composer:

```
composer require easy2dev/multi-tenant-wizard
```

2. Verify the installation by running `php artisan`. If the package commands are listed, the installation was successful.

## Usage

Easy2Dev Laravel Tenant Migrate provides the following artisan commands for managing tenant databases:

#### Create Postgresql Database

```shell
php artisan tenant:database:create [database-name]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-name` | **Required**. The name of the specific tenant database to be create|

#### Migrate a Specific Tenant Database

```shell
php artisan tenant:migrate [database-name] [--path=]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-name` | **Required**. The name of the specific tenant database to be migrate|
| `--path=` | **Optional**. The path to the schemas to be migrated (Default location is /database/migrations/tenant)|

#### Migrate All Tenant Database

```shell
php artisan tenant:migrate:all [database-prefix] [--path=]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-prefix` | **Required**. The common prefix shared by the names of the tenant databases to be migrated|
| `--path=` | **Optional**. The path to the schemas to be migrated (Default location is /database/migrations/tenant)|


#### Migrate Rollback All Tenant Databases

```shell
php artisan tenant:migrate:rollback [database-prefix] [--step=]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-prefix` | **Required**. The common prefix shared by the names of the tenant databases to be rollback|
| `--steps=` | **Optional**. Specifies the number of migration steps to roll back.|

#### Migrate Refresh All Tenant Databases

```shell
php artisan tenant:migrate:refresh [database-prefix] [--step=]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-prefix` | **Required**. The common prefix shared by the names of the tenant databases to be refresh|
| `--path=` | **Optional**. The path to the schemas to be migrated (Default location is /database/migrations/tenant)|

#### Migrate Reset All Tenant Databases

```shell
php artisan tenant:migrate:reset [database-prefix]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-prefix` | **Required**. The common prefix shared by the names of the tenant databases to be reset|

#### Seed Data for All Tenant Databases

```shell
php artisan tenant:db:seed [database-prefix]
```

| Parameter | Description                 |
| :-------- | :-------------------------  |
| `database-prefix` | **Required**. The common prefix shared by the names of the tenant databases where data will be seeded|


## License
This project is licensed under the MIT License.

## Acknowledgments
Special thanks to our contributors and supporters for their valuable contributions to this project.

## Author

[@SachinthaMadhawa](https://www.github.com/sachintha00)