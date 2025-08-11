<?php

namespace App\Filament\Admin\Resources\ContaResource\Pages;

use App\Filament\Admin\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContas extends ListRecords
{
    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
