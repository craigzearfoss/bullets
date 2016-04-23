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

use Illuminate\Database\Eloquent\Model;

class IlluminateBulleted extends Model
{
    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    public $table = 'bulleted';
}
