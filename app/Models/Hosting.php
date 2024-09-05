<?php

namespace Modules\Hosting\Models;

use Modules\Account\Models\Payment;
use Modules\Base\Models\BaseModel;
use Modules\Hosting\Models\Domain;
use Modules\Hosting\Models\Package;

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
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Add relationship to Package
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Add relationship to Payment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

}
