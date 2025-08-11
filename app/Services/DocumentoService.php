<?php
namespace App\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class DocumentoService
{
    public function gerarPDF(string $view, array $data, string $nomeArquivo): string
    {
        $html = View::make("documentos.$view", $data)->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $output = $dompdf->output();
        $path = "documentos/$nomeArquivo.pdf";
        Storage::put("public/$path", $output);

        return "storage/$path";
    }

    public function gerarDOCX(string $view, array $data, string $nomeArquivo): string
    {
        $html = View::make("documentos.$view", $data)->render();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        $path = "documentos/$nomeArquivo.docx";
        $fullPath = storage_path("app/public/$path");

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($fullPath);

        return "storage/$path";
    }
}
