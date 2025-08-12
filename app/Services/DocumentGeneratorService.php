<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentGeneratorService
{
    public static function generateDocx(string $templatePath, array $data, string $outputName): string
    {
        $template = new TemplateProcessor($templatePath);

        foreach ($data as $key => $value) {
            $template->setValue($key, $value);
        }

        $filePath = storage_path("app/public/{$outputName}.docx");
        $template->saveAs($filePath);

        return $filePath;
    }

    public static function generatePdfFromDocx(string $docxPath, string $outputName): string
    {
        // Aqui usamos DomPDF como exemplo (você pode renderizar HTML também)
        $pdfPath = storage_path("app/public/{$outputName}.pdf");

        $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
        $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

        $tmpHtml = storage_path("app/tmp/{$outputName}.html");
        @mkdir(dirname($tmpHtml), 0777, true);
        $htmlWriter->save($tmpHtml);

        $htmlContent = file_get_contents($tmpHtml);
        $pdf = Pdf::loadHTML($htmlContent);
        $pdf->save($pdfPath);

        return $pdfPath;
    }
}
