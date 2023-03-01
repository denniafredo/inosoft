<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Setup

- Database : Mongodb
- Database Name : inosoftdb

# Setting on .env
- MONGO_DB_HOST=127.0.0.1
- MONGO_DB_PORT=27017
- MONGO_DB_DATABASE=inosoftdb
- MONGO_DB_USERNAME=
- MONGO_DB_PASSWORD=

## Run this command
- composer install
- add to config/app.php providers -> Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
- add to config/app.php providers -> Jenssegers\Mongodb\MongodbServiceProvider::class,
- add to config/app.php aliases -> Tymon\JWTAuth\Facades\JWTAuth::class,
- add to config/app.php aliases -> Tymon\JWTAuth\Facades\JWTFactory::class,
- php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
- php artisan jwt:secret
- php artisan serve

## Postman
- Use inosoft.postman_collection.json
