<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEntreprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string("website");
            $table->string("maison");
            $table->string("phone");
            $table->string("address");
            $table->boolean("indepndant");
            $table->text("description");
            $table->string("facebook");
            $table->string("instagram");
            $table->string("twitter");
            $table->text("sous_labels");
            $table->text("artists");
            $table->text("genres");
            $table->string("logo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn([
                "website",
                "maison",
                "phone",
                "address",
                "indepndant",
                "description",
                "facebook",
                "instagram",
                "twitter",
                "sous_labels",
                "artists",
                "genres",
            ]);
        });
    }
}
