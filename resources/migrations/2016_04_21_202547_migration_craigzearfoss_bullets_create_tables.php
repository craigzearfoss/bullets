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
 * @version    0.0.7
 * @author     Craig Zearfoss
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2016, Craig Zearfpss
 * @link       http://craigzearfoss.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationCraigzearfossBulletsCreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bullets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('comment');
            $table->integer('sequence')->default(9999990)->unsigned();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bullets');
    }
}
