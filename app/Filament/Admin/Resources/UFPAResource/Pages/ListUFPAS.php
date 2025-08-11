<?php

namespace App\Filament\Admin\Resources\UFPAResource\Pages;

use App\Filament\Admin\Resources\UFPAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUFPAS extends ListRecords
{
    protected static string $resource = UFPAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
