<?php

/**
 * Part of the Bullets package.
 *
 * @package    Bullets
 * @version    0.0.8
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
    public $timestamps = true;

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
        'namespace',
    ];

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
}
