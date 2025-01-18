<?php

namespace Modules\Hosting\Models;

use Modules\Base\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;

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

    public function migration(Blueprint $table): void
    {
        $table->id();

        $table->string('title');
        $table->longText('unique_name');
        $table->longText('description')->nullable();
        $table->string('plan')->nullable();
        $table->decimal('price', 11)->nullable();
        $table->decimal('setup_fee', 11)->nullable();
        $table->integer('no_of_days')->nullable();
        $table->boolean('is_default')->nullable()->default(false);
        $table->boolean('published')->nullable()->default(false);

    }

}
