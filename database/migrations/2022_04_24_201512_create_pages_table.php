<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('custom_id')
                    ->default('')
                    ->index();
            
            $table->string('user_id')
                    ->nullable()
                    ->index();
            
            $table->string('link_id')
                    ->nullable()
                    ->index();
            
            $table->text('html');
            $table->string('visibility',15)->default('anyone');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
