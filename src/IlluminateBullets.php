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

namespace Cartalyst\Bullets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IlluminateBullets extends Model
{
    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    public $table = 'bullets';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'comment',
        'sequence',
        'count',
        'namespace',
    ];

    /**
     * The bulleted entities model.
     *
     * @var string
     */
    protected static $bulletedModel = 'Craigzearfoss\Bullets\IlluminateBulleted';

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->exists) {
            $this->bulleted()->delete();
        }

        return parent::delete();
    }

    /**
     * Returns the polymorphic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bulletable()
    {
        return $this->morphTo();
    }

    /**
     * Returns this bullet bulleted entities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bulleted()
    {
        return $this->hasMany(static::$bulletedModel, 'bullet_id');
    }

    /**
     * Finds a bullet by its comment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $comment
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComment(Builder $query, $comment)
    {
        return $query->whereName($comment);
    }

    /**
     * Finds a bullet by its sequence.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $sequence
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSequence(Builder $query, $sequence)
    {
        return $query->whereName($sequence);
    }

    /**
     * Returns the bulleted entities model.
     *
     * @return string
     */
    public static function getBulletedModel()
    {
        return static::$bulletedModel;
    }

    /**
     * Sets the bulleted entities model.
     *
     * @param  string  $bulletedModel
     * @return void
     */
    public static function setBulletedModel($bulletedModel)
    {
        static::$bulletedModel = $bulletedModel;
    }
}
