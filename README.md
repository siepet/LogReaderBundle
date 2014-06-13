Log Reader Bundle
=========

LogReaderBundle allows you to check your Symfony2 application logs using parsed logs viewed on table.

  - Sort them by date / channel / level
  - Filter them by channel or filter
  - Set the range of date to view logs by

Installation
--------------
Be sure you have installed MongoDB on your machine with extension enabled in your `php.ini`:
```json
extension=mongo.so
```
also remember about the proper configuration in `config.yml` for `doctrine_mongodb`:

```yml
doctrine_mongodb:
    connections:
        default:
            server: mongodb://host:port/database_name
            options:
                username: username
                password: password
    default_database: default_database_name
    document_managers:
        default:
            auto_mapping: true
```

Add to your `composer.json` the requirment of LogReaderBundle
```json
"require": "symfony/logreader-bundle": "dev-master",
```
And be sure, you have dependencies installed:
```json
    "require": {
        "php": ">=5.3.3",
        "doctrine/mongodb": ">=1.1.5,<2.0",
        "doctrine/mongodb-odm": "1.0.*@dev",
        "doctrine/mongodb-odm-bundle": "3.0.*@dev",
        "symfony/symfony": "2.3.*",
        "twig/extensions": "1.0.*",
        "symfony/assetic-bundle": "2.3.*",
        "symfony/monolog-bundle": "2.3.*"
    },
```

Then simply run
```sh
$ php composer.phar update
```
After it's done you add following configuration to

`app/config/config.yml`:

```yml
imports:
    - { resource: "@LogReaderBundle/Resources/config/services.yml" }

assetic:
    ...
    bundles: [ LogReaderBundle ]
    ...

log_reader:
    log_folder: "/full/path/to/folder/with/log/files/"
    log_file: "logfile.log"
```




`app/config/routing.yml`:

```yml
log_reader_logreader:
    resource: "@LogReaderBundle/Resources/config/routing.yml"
    prefix:   /
```



Due to problems with autoloading namespaces, you might have to add, if not done automatically, to `vendor/composer/autoload_namespaces.php` following line:
```php
'Symfony\\Bundle\\LogReaderBundle' => array($vendorDir . '/symfony/logreader-bundle'),
```


After that you should be able to load your app and just simply add `/logreader` to URI and you should be redirected. 
License
----

License can be found under the LICENSE file.

