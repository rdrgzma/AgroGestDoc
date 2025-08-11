<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentoController;




Route::get('/documento/contrato/{person}', [DocumentoController::class, 'gerarContrato']);

