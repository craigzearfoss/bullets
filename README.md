# bullets

---

## Installation

* Add the repositry to your composer.json file in the require section.
    > "require": {
    >     "craigzearfoss/bullets": "dev-master",
    > },

* Run composer install.
    > composer install -o

* Add the BulletsServiceProvider to app/config.php in the providers section.
    >   Craigzearfoss\Bullets\BulletsServiceProvider::class,


---

# Usage

* In your model add the BulletableTrait.
    > ...
    > use Craigzearfoss\Bullets\BulletableTrait;
    >
    > class MyModel extends Model
    > {
    >     use BulletableTrait;
    > ...

* To get the bullets in the controller:
    > $bullets = $myModel->bullets()->get();

* To sync the bullets when storing or updating:
    > $myModel->syncBullets(isset($data['bullet_list']) ? $data['bullet_list'] : []);

* In the forms blade templates:
    > <div class="form-group">
    >    {!! Form::label('bullet_list', 'Bullet Points:') !!}
    >   {!! Form::select('bullet_list[]', $myModel->bullets()->lists('comment', 'comment')->toArray(), array_keys($myModel->bullets()->lists('comment', 'comment')->toArray()), ['id' => 'bullet_list', 'class' => 'form-control bullet_list', 'multiple']) !!}
    > </div>

* To display them in a blade template:
    > @if (!empty($bullets))
    >     <ul>
    >         @foreach($bullets as $bullet)
    >             <li>{{ $bullet->comment }}</li>
    >         @endforeach
    >     </ul>
    > @endif
