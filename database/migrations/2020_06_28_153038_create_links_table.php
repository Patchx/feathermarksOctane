<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('custom_id')
                    ->default('')
                    ->index();
            $table->string('user_id')
                    ->nullable()
                    ->index();
            $table->string('folder_id')
                    ->nullable()
                    ->index();
            $table->string('category', 20)
                    ->default('personal')
                    ->index();
            $table->string('name', 100)
                    ->default('');
            $table->text('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
