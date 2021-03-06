<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTaggableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_taggable', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('tag_id');
            $table->morphs('taggable');

            $table->foreign('tag_id')->references('id')->on('tags')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_taggable');
    }
}
