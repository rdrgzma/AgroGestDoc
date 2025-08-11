<?php

namespace App\Filament\Admin\Resources\ContaResource\Pages;

use App\Filament\Admin\Resources\ContaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConta extends EditRecord
{
    protected static string $resource = ContaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
