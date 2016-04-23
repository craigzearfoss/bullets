<?php

/**
 * Part of the Bullets package.
 *
 * @package    Bullets
 * @version    0.0.6
 * @author     Craig Zearfoss
 * @license    MIT License
 * @copyright  (c) 2011-2016, Craig Zearfoss
 * @link       http://craigzearfoss.com
 */

namespace Craigzearfoss\Bullets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IlluminateBullet extends Model
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
