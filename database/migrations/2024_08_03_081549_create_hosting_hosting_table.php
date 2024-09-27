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
        Schema::create('hosting_hosting', function (Blueprint $table) {
            $table->id();

            $table->decimal('amount', 11)->nullable();
            $table->integer('http_code')->nullable();
            $table->dateTime('expiry_date', 6)->nullable();
            $table->dateTime('upgrade_date', 6)->nullable();
            $table->dateTime('last_upgrade_date', 6)->nullable();
            $table->string('log')->nullable();
            $table->boolean('paid')->nullable()->default(false);
            $table->boolean('completed')->nullable()->default(false);
            $table->boolean('successful')->nullable()->default(false);
            $table->boolean('status')->nullable()->default(false);
            $table->boolean('is_new')->nullable()->default(false);
            $table->boolean('is_update')->nullable()->default(false);
            $table->boolean('is_registered')->nullable()->default(false);
            $table->boolean('is_cpaneled')->nullable()->default(false);
            $table->boolean('is_installed')->nullable()->default(false);
            $table->boolean('is_removed')->nullable()->default(false);
            $table->boolean('is_live')->nullable()->default(false);
            $table->boolean('is_synced')->nullable()->default(false);
            $table->integer('call_counter')->nullable();
            $table->boolean('has_error')->nullable()->default(false);
            $table->foreignId('domain_id')->nullable()->constrained('domain_domain')->onDelete('set null');
            $table->foreignId('package_id')->nullable()->constrained('hosting_package')->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained('account_payment')->onDelete('set null');
            $table->foreignId('server_id')->nullable()->constrained('hosting_server')->onDelete('set null');
            $table->foreignId('partner_id')->nullable()->constrained('partner_partner')->onDelete('set null');
            $table->integer('whmcs_order_id')->nullable();
            $table->boolean('is_in_whmcs')->nullable()->default(false);

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
        Schema::dropIfExists('hosting_hosting');
    }
};
