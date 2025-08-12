<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Ufpa;
use App\Services\DocumentoService;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentoController extends Controller
{

    public function gerarContrato(Person $person)
    {

       // DocumentoService::gerarPDFSpatie('contrato', $person,$person-
        $pdf = Pdf::loadView('documentos.contrato', compact('person'))->setPaper('a4');
        return $pdf->download('contrato_'.$person->nome.'.pdf');

    }

    public function gerarDeclaracaoPosse(Person $person)
    {
        $renda = [
            'propria' => 18000.00,
            'externa' => 5648.00,
        ];

        $pessoa = $person;

        $arquivo = 'declaracao_posse_' . $person->nome;

        $pdf = Pdf::loadView('documentos.declaracao_posse_renda', compact(['pessoa','renda']))->setPaper('a4');
        return $pdf->download($arquivo.'.pdf');

    }

    public function gerarCAF(Person $person)
    {
        $renda = [
            'propria' => 31200.00,
            'externa' => 0.00,
            'beneficios' => 9000.00,
        ];
        $person = $person::with('ufpas')->first();
        $pessoa = $person;
        $ufpa = $person->ufpas()->first();
        $conjuge = $person->conjuge; // Supondo que você relacione isso no model
        $view = $conjuge ? 'formulario_caf_casal' : 'formulario_caf_solteiro';
        $nome = 'formulario_caf_' . $pessoa->nome;
        $pdf = Pdf::loadView('documentos.'.$view, compact(['pessoa','ufpa','renda','conjuge']))->setPaper('a4');
        return $pdf->download($nome.'.pdf');

    }


}


