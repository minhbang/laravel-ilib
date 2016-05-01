<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadEbookTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_ebook', function (Blueprint $table) {
            $table->integer('reader_id')->unsigned();
            $table->integer('ebook_id')->unsigned();
            $table->dateTime('read_at');
            $table->primary(['ebook_id', 'reader_id', 'read_at']);
            $table->foreign('reader_id')->references('user_id')->on('readers')->onDelete('cascade');
            $table->foreign('ebook_id')->references('id')->on('ebooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('read_ebook');
    }

}
