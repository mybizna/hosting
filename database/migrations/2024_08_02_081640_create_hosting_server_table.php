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
        Schema::create('hosting_server', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('domain');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('auth_type')->nullable();
            $table->integer('total_accounts')->nullable();
            $table->integer('limiting')->nullable();
            $table->boolean('is_full')->nullable()->default(false);
            $table->string('nameserver_1')->nullable();
            $table->string('nameserver_2')->nullable();
            $table->string('nameserver_3')->nullable();
            $table->string('nameserver_4')->nullable();
            $table->boolean('published')->nullable()->default(false);
            $table->string('ip_adsdress');

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_server');
    }
};
