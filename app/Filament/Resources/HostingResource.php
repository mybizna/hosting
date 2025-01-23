<?php

namespace Modules\Hosting\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Models\Hosting;

class HostingResource extends BaseResource
{
    protected static ?string $model = Hosting::class;

    protected static ?string $slug = 'hosting/hosting';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

}
