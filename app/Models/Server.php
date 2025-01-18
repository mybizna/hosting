<?php

namespace Modules\Hosting\Models;

use Modules\Base\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Server extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['id', 'title', 'domain', 'username', 'password', 'auth_type', 'total_accounts', 'limiting', 'is_full', 'nameserver_1', 'nameserver_2', 'nameserver_3', 'nameserver_4', 'published', 'ip_address'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_server";

    public function migration(Blueprint $table): void
    {
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

    }

}
