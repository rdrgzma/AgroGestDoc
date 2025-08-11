<?php

namespace App\Filament\Admin\Resources\DocumentoGeradoResource\Pages;

use App\Filament\Admin\Resources\DocumentoGeradoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoGerados extends ListRecords
{
    protected static string $resource = DocumentoGeradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
