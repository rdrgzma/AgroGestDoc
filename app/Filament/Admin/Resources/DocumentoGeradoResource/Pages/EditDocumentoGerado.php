<?php

namespace App\Filament\Admin\Resources\DocumentoGeradoResource\Pages;

use App\Filament\Admin\Resources\DocumentoGeradoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoGerado extends EditRecord
{
    protected static string $resource = DocumentoGeradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
