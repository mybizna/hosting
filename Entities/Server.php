<?php

namespace Modules\Hosting\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Server extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['id', 'title', 'domain', 'username', 'password', 'auth_type', 'total_accounts', 'limiting', 'is_full', 'nameserver_1', 'nameserver_2', 'nameserver_3', 'nameserver_4', 'published', 'ip_address'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['title'];

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
    protected $table = "hosting_server";

    /**
     * List of fields to be migrated to the datebase when creating or updating model during migration.
     *
     * @param Blueprint $table
     * @return void
     */
    public function fields(Blueprint $table = null): void
    {
        $this->fields = $table ?? new Blueprint($this->table);

        $this->fields->increments('id')->html('hidden');
        $this->fields->string('title')->html('text');
        $this->fields->string('domain')->html('text');
        $this->fields->string('username')->nullable()->html('text');
        $this->fields->string('password')->nullable()->html('text');
        $this->fields->string('auth_type')->nullable()->html('text');
        $this->fields->integer('total_accounts')->nullable()->html('text');
        $this->fields->integer('limiting')->nullable()->html('text');
        $this->fields->boolean('is_full')->nullable()->html('switch')->default(false);
        $this->fields->string('nameserver_1')->nullable()->html('text');
        $this->fields->string('nameserver_2')->nullable()->html('text');
        $this->fields->string('nameserver_3')->nullable()->html('text');
        $this->fields->string('nameserver_4')->nullable()->html('text');
        $this->fields->boolean('published')->nullable()->html('switch')->default(false);
        $this->fields->string('ip_adsdress')->html('text');

    }

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure['table'] = ['title', 'published', 'is_full', 'total_accounts', 'limiting', 'ip_address'];
        $structure['form'] = [
            ['label' => 'Server Detail', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => ['title', 'published', 'is_full', 'total_accounts', 'limiting', 'ip_address']],
            ['label' => 'Server Logging Details', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => ['domain', 'username', 'password', 'auth_type',]],
            ['label' => 'Server Nameserver', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => [ 'nameserver_1', 'nameserver_2', 'nameserver_3', 'nameserver_4']],
        ];
        $structure['filter'] = ['title', 'published', 'is_full', 'total_accounts', 'limiting', 'ip_address'];
        return $structure;
    }
}
