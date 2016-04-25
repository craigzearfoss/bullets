# bullets

---

## Installation

* Add the repository to your composer.json file in the require section.
    ```javascript
    "require": {
        "craigzearfoss/bullets": "dev-master"
    },
    ```

* Run composer install.
    > `composer install -o`

* Add the BulletsServiceProvider to app/config.php in the providers section.
    ```php
    Craigzearfoss\Bullets\BulletsServiceProvider::class,
    ```


---

# Usage

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
           {!! Form::select('bullet_list[]', $myModel->bullets()->lists('comment', 'comment')->toArray(), array_keys($myModel->bullets()->lists('comment', 'comment')->toArray()), ['id' => 'bullet_list', 'class' => 'form-control bullet_list', 'multiple']) !!}
        </div>
       ```
       
    2. Add select.js from https://select2.github.io/
        ```html
        <script type="text/javascript" src="js/select2.min.js"></script>
        ```
        
    3. Add the following JavaScript to the page.
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
