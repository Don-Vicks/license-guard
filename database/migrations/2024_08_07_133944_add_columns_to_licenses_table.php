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
        Schema::table('licenses', function (Blueprint $table) {
            //
            $table->integer('number_of_accesses')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            //
            Schema::dropIfExists('number_of_accesses');
            Schema::dropIfExists('last_accessed_at');



        });
    }
};
