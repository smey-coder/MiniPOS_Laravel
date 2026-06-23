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
        Schema::table('job', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('location');
            $table->dropColumn('deadline');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job', function (Blueprint $table) {
            $table->string('company');
            $table->string('location')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status',['open','closed'])->default('open');
        });
    }
};
