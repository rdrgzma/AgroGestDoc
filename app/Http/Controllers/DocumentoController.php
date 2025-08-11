<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Services\DocumentoService;

class DocumentoController extends Controller
{
    public function gerarContrato(Person $person, DocumentoService $documento)
    {
        $dados = ['pessoa' => $person];
        $nomeArquivo = 'contrato_' . $person->id;

        $caminhoPdf = $documento->gerarPDF('contrato', $dados, $nomeArquivo);
        $caminhoDocx = $documento->gerarDOCX('contrato', $dados, $nomeArquivo);

        return response()->json([
            'pdf' => asset($caminhoPdf),
            'docx' => asset($caminhoDocx),
        ]);
    }

    public function gerarDeclaracaoPosse(Person $person, DocumentoService $documento)
    {
        $renda = [
            'propria' => 18000.00,
            'externa' => 5648.00,
        ];

        $dados = ['pessoa' => $person, 'renda' => $renda];
        $arquivo = 'declaracao_posse_' . $person->id;

        $pdf = $documento->gerarPDF('declaracao_posse_renda', $dados, $arquivo);
        $docx = $documento->gerarDOCX('declaracao_posse_renda', $dados, $arquivo);

        return response()->json([
            'pdf' => asset($pdf),
            'docx' => asset($docx),
        ]);
    }

    public function gerarCAF(Person $pessoa, Ufpa $ufpa, DocumentoService $doc)
    {
        $renda = [
            'propria' => 31200.00,
            'externa' => 0.00,
            'beneficios' => 9000.00,
        ];

        $conjuge = $pessoa->conjuge; // Supondo que você relacione isso no model

        $view = $conjuge ? 'formulario_caf_casal' : 'formulario_caf_solteiro';
        $dados = compact('pessoa', 'ufpa', 'renda', 'conjuge');

        $nome = 'formulario_caf_' . $pessoa->id;

        $pdf = $doc->gerarPDF($view, $dados, $nome);
        $docx = $doc->gerarDOCX($view, $dados, $nome);

        return response()->json([
            'pdf' => asset($pdf),
            'docx' => asset($docx),
        ]);
    }


}


