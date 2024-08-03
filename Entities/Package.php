<?php

namespace Modules\Hosting\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Package extends BaseModel
{

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = [ 'id', 'title', 'unique_name', 'description', 'plan', 'price', 'setup_fee', 'no_of_days', 'is_default', 'published', ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_package";

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
        $this->fields->string('title')->html('text');
        $this->fields->longText('unique_name')->html('text');
        $this->fields->longText('description')->nullable()->html('textarea');
        $this->fields->string('plan')->nullable()->html('text');
        $this->fields->decimal('price', 11)->nullable()->html('amount');
        $this->fields->decimal('setup_fee', 11)->nullable()->html('amount');
        $this->fields->integer('no_of_days')->nullable()->html('text');
        $this->fields->boolean('is_default')->nullable()->html('switch')->default(false);
        $this->fields->boolean('published')->nullable()->html('switch')->default(false);

    }

 


}
