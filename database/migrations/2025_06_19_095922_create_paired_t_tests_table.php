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
        Schema::create('paired_t_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->enum('class_type', ['control', 'experiment']);

            $table->decimal('mean_difference', 6, 3);         // ⬅️ Rata-rata selisih post - pre
            $table->decimal('t_statistic', 8, 4);             // ⬅️ Nilai t
            $table->integer('degrees_freedom');               // ⬅️ Derajat kebebasan
            $table->string('p_value_one_tailed');
            $table->string('p_value_two_tailed');
            $table->integer('n');                             // ⬅️ Jumlah data

            $table->string('interpretation');                 // ⬅️ Interpretasi hasil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paired_t_tests');
    }
};
