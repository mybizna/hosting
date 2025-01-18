<?php

namespace Modules\Hosting\Models;

use Modules\Account\Models\Payment;
use Modules\Base\Models\BaseModel;
use Modules\Hosting\Models\Domain;
use Modules\Hosting\Models\Package;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hosting extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['id', 'amount', 'http_code', 'expiry_date', 'upgrade_date', 'last_upgrade_date', 'log', 'paid', 'completed', 'successful', 'status', 'is_new', 'is_update', 'is_registered', 'is_cpaneled', 'is_installed', 'is_removed', 'is_live', 'is_synced', 'call_counter', 'has_error', 'domain_id', 'package_id', 'payment_id', 'server_id', 'partner_id', 'whmcs_order_id', 'is_in_whmcs'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_hosting";

    /**
     * Add relationship to Domain
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Add relationship to Package
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Add relationship to Payment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function migration(Blueprint $table): void
    {
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
        $table->foreignId('domain_id')->nullable()->constrained(table: 'domain_domain')->onDelete('set null');
        $table->foreignId('package_id')->nullable()->constrained(table: 'hosting_package')->onDelete('set null');
        $table->foreignId('payment_id')->nullable()->constrained(table: 'account_payment')->onDelete('set null');
        $table->foreignId('server_id')->nullable()->constrained(table: 'hosting_server')->onDelete('set null');
        $table->foreignId('partner_id')->nullable()->constrained(table: 'partner_partner')->onDelete('set null');
        $table->integer('whmcs_order_id')->nullable();
        $table->boolean('is_in_whmcs')->nullable()->default(false);
    }

}
