<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptureIdToClientPaymentAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_payment_authorizations', function (Blueprint $table) {
            $table->string('capture_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_payment_authorizations', function (Blueprint $table) {
            $table->dropColumn('capture_id');
        });
    }
}
