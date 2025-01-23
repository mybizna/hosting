<?php

namespace Modules\Hosting\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Models\Server;

class ServerResource extends BaseResource
{
    protected static ?string $model = Server::class;

    protected static ?string $slug = 'hosting/server';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

}
