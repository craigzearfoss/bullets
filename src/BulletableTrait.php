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

trait BulletableTrait
{
    /**
     * The Eloquent bullets model name.
     *
     * @var string
     */
    protected static $bulletsModel = 'Craigzearfoss\Bullets\IlluminateBullet';

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
        $instance = new static;
        return $instance->createBulletsModel()
            ->whereNamespace($instance->getBulletEntityClassName())
            ->where('bulletable_id', $this->id);
    }

    /**
     * {@inheritdoc}
     */
    public static function allBullets()
    {
        $instance = new static;

        return $instance->createBulletsModel()
            ->whereNamespace($instance->getBulletEntityClassName());
    }

    /**
     * {@inheritdoc}
     */
    public function setBullets($bullets, $type = 'comment')
    {
        // Get the current entity bullets
        $entityBullets = $this->bullets->lists($type)->all();

        // Prepare the bullets to be added and removed
        $bulletsToAdd = array_diff($bullets, $entityBullets);
        $bulletsToDelete = array_diff($entityBullets, $bullets);

        // Delete the bullets
        if (!empty($bulletsToDelete)) {
            foreach($bulletsToDelete as $comment) {
                $this->deleteBullet($comment);
            }
        }

        // Add the bullets
        if (!empty($bulletsToAdd)) {
            foreach ($bulletsToAdd as $comment) {
                $this->addBullet($comment);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addBullet($comment)
    {
        $bullet = $this->createBulletsModel()->firstOrNew([
            'namespace' => $this->getBulletEntityClassName(),
            'bulletable_id' => $this->id,
            'comment' => $comment
        ]);

        if (! $bullet->exists) {

            // increment sequence
            $maxSequence = $this->createBulletsModel()
                ->whereNamespace($this->getBulletEntityClassName())
                ->where(function ($query) {
                    $query
                        ->Where('bulletable_id', $this->id)
                    ;
                })
                ->max('sequence');
            $bullet->sequence = $maxSequence + 1;

            $bullet->save();
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

        return $this->getEntityClassName();
    }

    /**
     * @param array $newBullets
     * @return bool
     */
    public function syncBullets($newBullets = [])
    {
        // determine if any bullets should be deleted
        $currentBullets = $this->bullets()->lists('comment', 'id')->toArray();
        $bulletsToDelete = array_udiff($currentBullets, $newBullets, 'strcmp');
        $newBullets = array_udiff($newBullets, $currentBullets, 'strcmp');

        // add the new bullets
        foreach ($newBullets as $comment) {
            $this->addBullet($comment);
        }

        // delete old bullets
        foreach ($bulletsToDelete as $comment) {
            $this->deleteBullet($comment);
        }

        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function deleteBullet($comment)
    {
        $bullet = $this->createBulletsModel()->firstOrNew([
            'namespace' => $this->getBulletEntityClassName(),
            'bulletable_id' => $this->id,
            'comment' => $comment
        ]);

        if ($bullet->exists) {
            $bullet->delete();
        }

        return true;
    }
}
