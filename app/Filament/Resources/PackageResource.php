<?php

namespace Modules\Hosting\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Models\Package;

class PackageResource extends BaseResource
{
    protected static ?string $model = Package::class;

    protected static ?string $slug = 'hosting/package';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


}
