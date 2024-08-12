<?php

namespace Modules\Hosting\Filament\Resources\HostingResource\Pages;

use Modules\Hosting\Filament\Resources\HostingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHosting extends EditRecord
{
    protected static string $resource = HostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
