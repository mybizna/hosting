<?php

namespace Modules\Hosting\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class HostingPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Hosting';
    }

    public function getId(): string
    {
        return 'hosting';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
