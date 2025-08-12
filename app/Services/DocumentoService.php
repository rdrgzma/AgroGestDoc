<?php
namespace App\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use function Spatie\LaravelPdf\Support\pdf;


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
        dd($output);
        $path = "documentos/$nomeArquivo.pdf";
        Storage::put("public/$path", $output);

        return "storage/$path";
    }

    public static function gerarPDFSpatie( string $view, $data, string $nomeArquivo)
    {
        pdf()->view('documentos.'.$view, compact('data'))
        ->name($nomeArquivo)
        ->download();
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
