<?php
namespace Modules\Hosting\Models;

use Base\Casts\Money;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Modules\Account\Models\Payment;
use Modules\Base\Models\BaseModel;
use Modules\Domain\Models\Domain;
use Modules\Hosting\Models\Package;

class Hosting extends BaseModel
{

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total' => Money::class, // Use the custom MoneyCast
    ];
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

        $table->integer('amount')->nullable();
        $table->string('currency')->default('USD');
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
        $table->unsignedBigInteger('domain_id')->nullable();
        $table->unsignedBigInteger('package_id')->nullable()->default(false);
        $table->unsignedBigInteger('payment_id')->nullable()->default(false);
        $table->unsignedBigInteger('server_id')->nullable()->default(false);
        $table->unsignedBigInteger('partner_id')->nullable()->default(false);

        $table->integer('whmcs_order_id')->nullable();
        $table->boolean('is_in_whmcs')->nullable()->default(false);
    }

    public function post_migration(Blueprint $table): void
    {
        $table->foreign('domain_id')->references('id')->on('domain_domain')->onDelete('set null');
        $table->foreign('package_id')->references('id')->on('hosting_package')->onDelete('set null');
        $table->foreign('payment_id')->references('id')->on('account_payment')->onDelete('set null');
        $table->foreign('server_id')->references('id')->on('hosting_server')->onDelete('set null');
        $table->foreign('partner_id')->references('id')->on('partner_partner')->onDelete('set null');
    }

}
