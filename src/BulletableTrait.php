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

trait BulletableTrait
{
    /**
     * The bullets delimiter.
     *
     * @var string
     */
    protected static $bulletDelimiter = ',';

    /**
     * The Eloquent bullets model name.
     *
     * @var string
     */
    protected static $bulletsModel = 'Craigzearfoss\Bullets\IlluminateBullet';

    /**
     * {@inheritdoc}
     */
    public static function getBulletDelimiter()
    {
        return static::$bulletDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public static function setBulletDelimiter($bulletDelimiter)
    {
        static::$bulletDelimiter = $bulletDelimiter;

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
            $instance->getBulletEntityClassName()
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
            'namespace' => $this->getBulletEntityClassName(),
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
        $namespace = $this->getBulletEntityClassName();

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
            $bulletDelimiter = preg_quote($this->getBulletDelimiter(), '#');

            $bullets = array_map('trim',
                preg_split("#[{$bulletDelimiter}]#", $bullets, null, PREG_SPLIT_NO_EMPTY)
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
     * Returns the entity class name.
     *
     * @return string
     */
    protected function getBulletEntityClassName()
    {
        if (isset(static::$entityNamespace)) {
            return static::$entityNamespace;
        }

        return $this->bullets()->getMorphClass();
    }
}
