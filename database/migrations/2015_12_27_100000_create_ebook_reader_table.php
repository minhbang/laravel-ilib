<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbookReaderTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebook_reader', function (Blueprint $table) {
            $table->integer('ebook_id')->unsigned();
            $table->integer('reader_id')->unsigned();
            $table->dateTime('expires_at');
            $table->nullableTimestamps();
            $table->primary(['ebook_id', 'reader_id']);
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
        Schema::drop('ebook_reader');
    }

}
