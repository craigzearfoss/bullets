<?php

/**
 * Part of the Bullets package.
 *
 * @package    Bullets
 * @version    0.0.0
 * @author     Craig Zearfoss
 * @license    MIT License
 * @copyright  (c) 2011-2016, Craig Zearfoss
 * @link       http://craigzearfoss.com
 */

namespace Craigzearfoss\Bullets;

use Illuminate\Database\Eloquent\Builder;

interface BulletableInterface
{
    /**
     * Returns the bullets delimiter.
     *
     * @return string
     */
    public static function getBulletsDelimiter();

    /**
     * Sets the bullets delimiter.
     *
     * @param  string  $bulletDelimiter
     * @return $this
     */
    public static function setBulletsDelimiter($bulletDelimiter);

    /**
     * Returns the Eloquent bullets model comment.
     *
     * @return string
     */
    public static function getBulletsModel();

    /**
     * Sets the Eloquent bullets model comment.
     *
     * @param  string  $model
     * @return void
     */
    public static function setBulletsModel($model);

    /**
     * Returns the entity Eloquent bullet model object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function bullets();

    /**
     * Returns all the bullets under the entity namespace.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function allBullets();

    /**
     * Returns the entities with only the given bullets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $bullets
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeWhereBullet(Builder $query, $bullets, $type = 'comment');

    /**
     * Returns the entities with one of the given bullets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $bullets
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeWithBullet(Builder $query, $bullets, $type = 'comment');

    /**
     * Attaches multiple bullets to the entity.
     *
     * @param  string|array  $bullets
     * @return bool
     */
    public function bullet($bullets);

    /**
     * Detaches multiple bullets from the entity or if no bullets are
     * passed, removes all the attached bullets from the entity.
     *
     * @param  string|array|null  $bullets
     * @return bool
     */
    public function unbullet($bullets = null);

    /**
     * Attaches or detaches the given bullets.
     *
     * @param  string|array  $bullets
     * @param  string  $type
     * @return bool
     */
    public function setBullets($bullets, $type = 'comment');

    /**
     * Attaches the given bullet to the entity.
     *
     * @param  string  $comment
     * @return void
     */
    public function addBullet($comment);

    /**
     * Detaches the given bullet from the entity.
     *
     * @param  string  $comment
     * @return void
     */
    public function removeBullet($comment);

    /**
     * Prepares the given bullets before being saved.
     *
     * @param  string|array  $bullets
     * @return array
     */
    public function prepareBullets($bullets);

    /**
     * Creates a new model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createBulletsModel();
}
