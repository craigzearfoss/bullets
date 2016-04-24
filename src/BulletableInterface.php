<?php

/**
 * Part of the Bullets package.
 *
 * @package    Bullets
 * @version    0.0.11
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
     * Attaches multiple bullets to the entity.
     *
     * @param  string|array  $bullets
     * @return bool
     */
    public function bullet($bullets);

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
     * Creates a new model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createBulletsModel();

    /**
     * @param array $newBullets
     * @return bool
     */
    public function syncBullets($newBullets = []);
}
