Log Reader Bundle
=========

LogReaderBundle allows you to check your Symfony2 application logs using parsed logs viewed on table.

  - Sort them by date / channel / level
  - Filter them by channel or filter
  - Set the range of date to view logs by

Installation
--------------
Add to your `composer.json`
```json
"require": "symfony/logreader-bundle": "dev-master",
```
Then simply run
```sh
$ php composer.phar update
```
After it's done you add following configuration to

`app/config/config.yml`:

```yml
log_reader:
    log_folder: ""
    log_file: ""
```
`app/config/routing.yml`:

```yml
log_reader_logreader:
    resource: "@LogReaderBundle/Resources/config/routing.yml"
    prefix:   /
```


Where the `log_folder` is full path to directory with logs and `log_file` is log file being shown.

Due to problems with autoloading, you might have to add to `vendor/composer/autoload_namespaces.php` following line:
```php
'Symfony\\Bundle\\LogReaderBundle' => array($vendorDir . '/symfony/logreader-bundle'),
```


After that you should be able to load your app and just simply add `/logreader` to URI and you should be redirected. 
License
----

License can be found under the LICENSE file.

