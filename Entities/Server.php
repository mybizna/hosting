<?php

namespace Modules\Hosting\Entities;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_server";

}
