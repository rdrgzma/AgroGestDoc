<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentoController;




Route::get('/documento/contrato/{person}', [DocumentoController::class, 'gerarContrato']);

// gerarDeclaracaoPosse
Route::get('/documento/declaracao_posse/{person}', [DocumentoController::class, 'gerarDeclaracaoPosse']);

//gerarCAF
Route::get('/documento/caf/{pessoa}', [DocumentoController::class, 'gerarCAF']);

//
