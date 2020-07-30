<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_details', function (Blueprint $table) {
            $table->id();
            $table->mediumText('biography');
            $table->string('picture')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('admin_id');
            $table->string('country')->nullable();
            $table->string('mobile')->nullable();
            $table->string('adresse')->nullable();
            $table->string('gender')->nullable()->default('Male');
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_details');
    }
}
