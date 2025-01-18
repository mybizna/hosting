<?php
namespace Modules\Hosting\Models;

use Base\Casts\Money;
use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Models\BaseModel;

class Package extends BaseModel
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
    protected $fillable = ['id', 'title', 'unique_name', 'description', 'plan', 'price', 'setup_fee', 'no_of_days', 'is_default', 'published'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "hosting_package";

    public function migration(Blueprint $table): void
    {

        $table->string('title');
        $table->longText('unique_name');
        $table->longText('description')->nullable();
        $table->string('plan')->nullable();
        $table->integer('price')->nullable();
        $table->integer('setup_fee')->nullable();
        $table->string('currency')->default('USD');
        $table->integer('no_of_days')->nullable();
        $table->boolean('is_default')->nullable()->default(false);
        $table->boolean('published')->nullable()->default(false);

    }

}
