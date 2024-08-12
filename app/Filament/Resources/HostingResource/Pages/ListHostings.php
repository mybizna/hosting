<?php

namespace Modules\Hosting\Filament\Resources\HostingResource\Pages;

use Modules\Hosting\Filament\Resources\HostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHostings extends ListRecords
{
    protected static string $resource = HostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
