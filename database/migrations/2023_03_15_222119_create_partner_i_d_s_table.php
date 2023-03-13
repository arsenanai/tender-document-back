<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_i_d_s', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('subpartner_id');
            $table->string('comments');
            $table->timestamps();
            $table->foreignId('number_id')
                ->constrained('numbers')
                ->onDelete('cascade');
            $table->foreignId('subpartner_id')
                ->constrained('subpartners')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_i_d_s', function (Blueprint $table) {
            $table->dropForeign(['subpartner_id']);
            $table->dropForeign(['number_id']);
        });
        Schema::dropIfExists('partner_i_d_s');
    }
};
