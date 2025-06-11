<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relación con usuario dueño

            $table->string('name');                  // nombre de la mascota
            $table->string('species');              // especie (ej: dog, cat)
            $table->string('breed')->nullable();    // raza, puede ser nula
            $table->date('dob')->nullable();        // fecha de nacimiento
            $table->enum('gender', ['male', 'female'])->nullable(); // género
            $table->float('weight')->nullable();    // peso (valor numérico)
            $table->string('weight_unit')->nullable(); // unidad de peso (kg, lb, etc.)
            $table->text('allergies')->nullable();  // alergias
            $table->text('health_notes')->nullable(); // notas médicas

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

