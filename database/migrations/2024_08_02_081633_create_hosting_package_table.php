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
        Schema::create('hosting_package', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('unique_name');
            $table->longText('description')->nullable();
            $table->string('plan')->nullable();
            $table->decimal('price', 11)->nullable();
            $table->decimal('setup_fee', 11)->nullable();
            $table->integer('no_of_days')->nullable();
            $table->boolean('is_default')->nullable()->default(false);
            $table->boolean('published')->nullable()->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_package');
    }
};
