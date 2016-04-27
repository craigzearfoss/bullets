Bullets for Laravel 5
=====================

This package allows you to attach bullet points to an Eloquent model in [**Laravel 5**](http://laravel.com/).


Installation
------------

It can be found on [Packagist](https://packagist.org/packages/craigzeaross/bullets).
The recommended way is through [composer](http://getcomposer.org).

Edit `composer.json` and add:

```json
{
    "require": {
        "craigzearfoss/bullets": "dev-master"
    }
}
```

And install dependencies:
```bash
$ composer update
```


If you do not have [**Composer**](https://getcomposer.org) installed, run these two commands:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```


Usage
-----

Find the `providers` array key in `config/app.php` and register the **Bullets Service Provider**.

```php
'providers' => array(
    // ...

    Craigzearfoss\Bullets\BulletsServiceProvider::class,
)
```

Configuration
-------------

* In your model add the BulletableTrait.
    ```php
    use Craigzearfoss\Bullets\BulletableTrait;
    
    class MyModel extends Model
    {
        use BulletableTrait;
    ```

* To get the bullets in the controller:
    ```php
    $bullets = $myModel->bullets()->get();
    ```

* To sync the bullets when storing or updating:
    ```php
    $myModel->syncBullets(isset($data['bullet_list']) ? $data['bullet_list'] : []);
    ```

* To add the bullets to you forms blade templates:

    1. Add the form select element.
        ```html
        <div class="form-group">
            {!! Form::label('bullet_list', 'Bullet Points:') !!}
           {!! Form::select('bullet_list[]', $myModel->bullets()->lists('comment', 'comment')->toArray(), array_keys($myModel->bullets()->lists('comment', 'comment')->toArray()), ['id' => 'bullet_list', 'class' => 'form-control select2-bullet-list', 'multiple']) !!}
        </div>
       ```
       
    2. Add select.js from https://select2.github.io/
        ```html
        <script type="text/javascript" src="js/select2.min.js"></script>
        ```
        
    3. Add the following to your css for the form element. This makes the bullet select list items width 100%.
        ```css
        .select2-bullet-list + .select2-container--default .select2-selection--multiple .select2-selection__choice {
            width: 100%;
        }
        ```
    
    4. Add the following JavaScript to the page.
        ```javascript
        <script type="text/javascript">
            $("#bullet_list").select2({
                placeholder: "Add Bullet Points",
                tags: true,
                tokenSeparators: ["\n"],
                maximumSelectionLength: 255
            });
        </script>
        ```

* To display the bullet in a blade template:
    ```html
    @if (!empty($bullets))
        <ul>
            @foreach($bullets as $bullet)
                <li>{{ $bullet->comment }}</li>
            @endforeach
        </ul>
    @endif
    ```


Changelog
---------

[See the CHANGELOG file](https://github.com/craigzearfoss/bullets/blob/master/CHANGELOG.md)


Support
-------

[Please open an issue on GitHub](https://github.com/craigzearfoss/bullets/issues)


Contributor Code of Conduct
---------------------------

Please note that this project is released with a Contributor Code of Conduct.
By participating in this project you agree to abide by its terms.


License
-------

GeocoderLaravel is released under the MIT License. See the bundled
[LICENSE](https://github.com/craigzearfoss/bullets/blob/master/LICENSE)
file for details.