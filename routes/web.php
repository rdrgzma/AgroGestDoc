<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentoController;



Route::redirect('/', '/admin');



Route::get('/documento/contrato/{person}', [DocumentoController::class, 'gerarContrato']);

// gerarDeclaracaoPosse
Route::get('/documento/declaracao_posse/{person}', [DocumentoController::class, 'gerarDeclaracaoPosse']);

//gerarCAF
Route::get('/documento/caf/{pessoa}', [DocumentoController::class, 'gerarCAF']);


// Adicione estas rotas ao arquivo routes/web.php



// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {

    // Download de documentos gerados
    Route::get('/documents/download/{file}', [DocumentController::class, 'downloadDocument'])
        ->name('download.document')
        ->where('file', '[A-Za-z0-9\-_\.]+');

    // Preview de documentos (para PDFs)
    Route::get('/documents/preview/{file}', [DocumentController::class, 'previewDocument'])
        ->name('preview.document')
        ->where('file', '[A-Za-z0-9\-_\.]+');

    // Download de templates
    Route::get('/documents/templates/download', [DocumentController::class, 'downloadTemplates'])
        ->name('download.templates');

    // API para listar documentos gerados
    Route::get('/api/documents/list', [DocumentController::class, 'listGeneratedDocuments'])
        ->name('api.documents.list');

    // Limpeza de documentos expirados (pode ser chamado via CRON)
    Route::post('/api/documents/cleanup', [DocumentController::class, 'deleteExpiredDocuments'])
        ->name('api.documents.cleanup');
});

// Rota adicional para limpeza automática (sem autenticação para CRON)
Route::post('/cron/documents/cleanup', [DocumentController::class, 'deleteExpiredDocuments'])
    ->name('cron.documents.cleanup');

/*
Exemplo de uso das rotas:

1. Para download de documento:
   GET /documents/download/cliente_joao_caf_declaracao_2025-01-01_12-00-00.docx

2. Para preview de documento:
   GET /documents/preview/cliente_joao_caf_declaracao_2025-01-01_12-00-00.pdf

3. Para download de templates:
   GET /documents/templates/download

4. Para listar documentos via API:
   GET /api/documents/list

5. Para limpeza de documentos expirados:
   POST /api/documents/cleanup
   POST /cron/documents/cleanup (para CRON jobs)
*/
