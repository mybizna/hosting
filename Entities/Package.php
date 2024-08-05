<?php

namespace Modules\Hosting\Entities;

use Modules\Base\Entities\BaseModel;

class Package extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['id', 'title', 'unique_name', 'description', 'plan', 'price', 'setup_fee', 'no_of_days', 'is_default', 'published'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_package";

}
