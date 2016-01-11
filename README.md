# Laravel ILib

## Install

* **Thêm vào file composer.json của app**
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/minhbang/laravel-ilib"
        }
    ],
    "require": {
        "minhbang/laravel-ilib": "dev-master"
    }
```
``` bash
$ composer update
```

* **Thêm vào file config/app.php => 'providers'**
```php
	Minhbang\ILib\ServiceProvider::class,
```

* **Publish config và database migrations**
```bash
$ php artisan vendor:publish
$ php artisan migrate
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
