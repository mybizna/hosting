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

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure['table'] = ['title', 'published', 'is_default', 'price', 'no_of_days','is_default','published'];
        $structure['form'] = [
            ['label' => 'Package Detail', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => ['title', 'unique_name', 'plan', 'price', 'setup_fee', 'no_of_days', 'is_default', 'published']],
            ['label' => 'Package Setting', 'class' => 'col-span-full md:col-span-6 md:pr-2', 'fields' => [ 'description']],
        ];
        $structure['filter'] = ['title', 'published', 'is_default', 'price', 'no_of_days','is_default','published'];
        
        return $structure;
    }


    /**
     * Define rights for this model.
     *
     * @return array
     */
    public function rights(): array
    {

    }
}
