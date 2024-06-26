<?php

namespace Modules\Hosting\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Hosting extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['id', 'amount', 'http_code', 'expiry_date', 'upgrade_date', 'last_upgrade_date', 'log', 'paid', 'completed', 'successful', 'status', 'is_new', 'is_update', 'is_registered', 'is_cpaneled', 'is_installed', 'is_removed', 'is_live', 'is_synced', 'call_counter', 'has_error', 'domain_id', 'package_id', 'payment_id', 'server_id', 'partner_id', 'whmcs_order_id', 'is_in_whmcs'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['domain_id__name'];

    /**
     * List of tables names that are need in this model during migration.
     *
     * @var array<string>
     */
    public array $migrationDependancy = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_hosting";

    /**
     * List of fields to be migrated to the datebase when creating or updating model during migration.
     *
     * @param Blueprint $table
     * @return void
     */
    public function fields(Blueprint $table = null): void
    {
        $this->fields = $table ?? new Blueprint($this->table);

        $this->fields->increments('id' )->html('hidden');
        $this->fields->decimal('amount', 11)->nullable()->html('amount');
        $this->fields->integer('http_code')->nullable()->html('text');
        $this->fields->dateTime('expiry_date', 6)->nullable()->html('datetime');
        $this->fields->dateTime('upgrade_date', 6)->nullable()->html('datetime');
        $this->fields->dateTime('last_upgrade_date', 6)->nullable()->html('datetime');
        $this->fields->string('log')->nullable()->html('text');
        $this->fields->boolean('paid')->nullable()->html('switch')->default(false);
        $this->fields->boolean('completed')->nullable()->html('switch')->default(false);
        $this->fields->boolean('successful')->nullable()->html('switch')->default(false);
        $this->fields->boolean('status')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_new')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_update')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_registered')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_cpaneled')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_installed')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_removed')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_live')->nullable()->html('switch')->default(false);
        $this->fields->boolean('is_synced')->nullable()->html('switch')->default(false);
        $this->fields->integer('call_counter')->nullable()->html('text');
        $this->fields->boolean('has_error')->nullable()->html('switch')->default(false);
        $this->fields->bigInteger('domain_id')->nullable()->html('recordpicker')->relation(['domain']);
        $this->fields->bigInteger('package_id')->nullable()->html('recordpicker')->relation(['hosting', 'package']);
        $this->fields->bigInteger('payment_id')->nullable()->html('recordpicker')->relation(['account', 'invoice']);
        $this->fields->bigInteger('server_id')->nullable()->html('recordpicker')->relation(['hosting', 'server']);
        $this->fields->integer('partner_id')->nullable()->html('recordpicker')->relation(['partner']);
        $this->fields->integer('whmcs_order_id')->nullable()->html('text');
        $this->fields->boolean('is_in_whmcs')->nullable()->html('switch')->default(false);

    }
    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure['table'] = ['domain_id', 'amount', 'http_code', 'expiry_date', 'upgrade_date', 'last_upgrade_date', 'log', 'paid', 'completed', 'successful', 'status', 'is_new', 'is_update', 'is_registered', 'is_cpaneled', 'is_installed', 'is_removed', 'is_live', 'is_synced', 'call_counter', 'has_error', 'package_id', 'payment_id', 'server_id', 'partner_id', 'whmcs_order_id', 'is_in_whmcs'];
        $structure['form'] = [
            ['label' => 'Hosting Detail', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => ['domain_id', 'amount', 'http_code',  'package_id', 'payment_id', 'server_id', 'partner_id','whmcs_order_id',]],
            ['label' => 'Hosting Status', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => [ 'paid', 'completed', 'successful', 'status', 'is_new', 'is_update','is_cpaneled',  'is_registered', ]],
            ['label' => 'Hosting Setting', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => [ 'is_installed', 'is_removed', 'is_live', 'is_synced', 'has_error','is_in_whmcs']],
            ['label' => 'Hosting Date', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => ['expiry_date', 'upgrade_date', 'last_upgrade_date','call_counter',]],
            ['label' => 'Hosting Log', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => [ 'log',  ]],
        ];
        $structure['filter'] = ['domain_id', 'amount', 'http_code', 'expiry_date', 'upgrade_date', 'last_upgrade_date', 'log', 'paid', 'completed', 'successful', 'status', 'is_new', 'is_update', 'is_registered', 'is_cpaneled', 'is_installed', 'is_removed', 'is_live', 'is_synced', 'call_counter', 'has_error', 'package_id', 'payment_id', 'server_id', 'partner_id', 'whmcs_order_id', 'is_in_whmcs'];
        
        return $structure;
    }


    /**
     * Define rights for this model.
     *
     * @return array
     */
    public function rights(): array
    {
        $rights = parent::rights();

        $rights['staff'] = ['view' => true];
        $rights['registered'] = ['view' => true];
        $rights['guest'] = [];

        return $rights;
    }
}