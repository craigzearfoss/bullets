<?php

/**
 * Part of the Bullets package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Bullets
 * @version    0.0.0
 * @author     Craig Zearfoss
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2016, Craig Zearfoss
 * @link       http://craigzearfoss.com
 */

namespace Cragzearfoss\Bullets;

use Illuminate\Database\Eloquent\Builder;

trait BulletableTrait
{
    /**
     * The bullets delimiter.
     *
     * @var string
     */
    protected static $delimiter = ',';

    /**
     * The Eloquent bullets model name.
     *
     * @var string
     */
    protected static $bulletsModel = 'Craigzearfoss\Bullets\IlluminateBullet';

    /**
     * The Slug generator method.
     *
     * @var string
     */
    protected static $slugGenerator = 'Illuminate\Support\Str::slug';

    /**
     * {@inheritdoc}
     */
    public static function getBulletsDelimiter()
    {
        return static::$delimiter;
    }

    /**
     * {@inheritdoc}
     */
    public static function setBulletsDelimiter($delimiter)
    {
        static::$delimiter = $delimiter;

        return get_called_class();
    }

    /**
     * {@inheritdoc}
     */
    public static function getBulletsModel()
    {
        return static::$bulletsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setBulletsModel($model)
    {
        static::$bulletsModel = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function bullets()
    {
        return $this->morphToMany(static::$bulletsModel, 'bulletable', 'bulleted', 'bulletable_id', 'bullet_id');
    }

    /**
     * {@inheritdoc}
     */
    public static function allBullets()
    {
        $instance = new static;

        return $instance->createBulletsModel()->whereNamespace(
            $instance->getEntityClassName()
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function scopeWhereBullet(Builder $query, $bullets, $type = 'comment')
    {
        $bullets = (new static)->prepareBullets($bullets);

        foreach ($bullets as $bullet) {
            $query->whereHas('bullets', function ($query) use ($type, $bullet) {
                $query->where($type, $bullet);
            });
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public static function scopeWithBullet(Builder $query, $bullets, $type = 'comment')
    {
        $bullets = (new static)->prepareBullets($bullets);

        return $query->whereHas('bullets', function ($query) use ($type, $bullets) {
            $query->whereIn($type, $bullets);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function bullet($bullets)
    {
        foreach ($this->prepareBullets($bullets) as $bullet) {
            $this->addBullet($bullet);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unbullet($bullets = null)
    {
        $bullets = $bullets ?: $this->bullets->lists('name')->all();

        foreach ($this->prepareBullets($bullets) as $bullet) {
            $this->removeBullet($bullet);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setBullets($bullets, $type = 'comment')
    {
        // Prepare the bullets
        $bullets = $this->prepareBullets($bullets);

        // Get the current entity bullets
        $entityBullets = $this->bullets->lists($type)->all();

        // Prepare the bullets to be added and removed
        $bulletsToAdd = array_diff($bullets, $entityBullets);
        $bulletsToDel = array_diff($entityBullets, $bullets);

        // Detach the bullets
        if (! empty($bulletsToDel)) {
            $this->unbullet($bulletsToDel);
        }

        // Attach the bullets
        if (! empty($bulletsToAdd)) {
            $this->bullet($bulletsToAdd);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addBullet($comment)
    {
        $bullet = $this->createBulletsModel()->firstOrNew([
            'sequence'  => 999999,
            'namespace' => $this->getEntityClassName(),
        ]);

        if (! $bullet->exists) {
            $bullet->comment = $comment;

            $bullet->save();
        }

        if (! $this->bullets->contains($bullet->id)) {
            $bullet->update([ 'count' => $bullet->count + 1 ]);

            $this->bullets()->attach($bullet);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeBullet($name)
    {
        $namespace = $this->getEntityClassName();

        $bullet = $this
            ->createBulletsModel()
            ->whereNamespace($namespace)
            ->where(function ($query) use ($name) {
                $query
                    ->orWhere('comment', $name)
                ;
            })
            ->first()
        ;

        if ($bullet) {
            $bullet->update(['count' => $bullet->count - 1]);

            $this->bullets()->detach($bullet);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepareBullets($bullets)
    {
        if (is_null($bullets)) {
            return [];
        }

        if (is_string($bullets)) {
            $delimiter = preg_quote($this->getBulletsDelimiter(), '#');

            $bullets = array_map('trim',
                preg_split("#[{$delimiter}]#", $bullets, null, PREG_SPLIT_NO_EMPTY)
            );
        }

        return array_unique(array_filter($bullets));
    }

    /**
     * {@inheritdoc}
     */
    public static function createBulletsModel()
    {
        return new static::$bulletsModel;
    }

    /**
     * Generate the bullet slug using the given comment.
     *
     * @param  string  $comment
     * @return string
     */
    protected function generateBulletSlug($comment)
    {
        return call_user_func(static::$slugGenerator, $comment);
    }

    /**
     * Returns the entity class name.
     *
     * @return string
     */
    protected function getEntityClassName()
    {
        if (isset(static::$entityNamespace)) {
            return static::$entityNamespace;
        }

        return $this->bullets()->getMorphClass();
    }
}