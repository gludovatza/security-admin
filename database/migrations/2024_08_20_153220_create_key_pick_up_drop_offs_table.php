<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('key_pick_up_drop_offs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('key_id')->constrained();

            $table->timestamp('pick_up_time');
            $table->unsignedBigInteger('pick_up_user_id');
            $table->foreign('pick_up_user_id')->references('id')->on('users');
            $table->text('pick_up_sign');
            $table->text('pick_up_security_sign');

            $table->timestamp('drop_off_time')->nullable();
            $table->unsignedBigInteger('drop_off_user_id')->nullable();
            $table->foreign('drop_off_user_id')->references('id')->on('users');
            $table->text('drop_off_sign')->nullable();
            $table->text('drop_off_security_sign')->nullable();

            $table->foreignId('company_id')->constrained(); // a 4 szintű hasManyThrough kapcsolat (kulcsesemény -> kulcs -> telephely -> vállalat) hiánya miatt inkább beteszem ide a company_id-t

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_pick_up_drop_offs');
    }
};
