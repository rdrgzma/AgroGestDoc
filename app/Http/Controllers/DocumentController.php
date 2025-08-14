<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class DocumentController extends Controller
{
    public function downloadDocument(Request $request, string $file): BinaryFileResponse
    {
        $filePath = storage_path('app/documents/generated/' . $file);

        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado');
        }

        // Verifica se o arquivo não é muito antigo (opcional - remove arquivos após 24h)
        if (filemtime($filePath) < strtotime('-24 hours')) {
            unlink($filePath);
            abort(404, 'Arquivo expirado');
        }

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function downloadTemplates(): BinaryFileResponse
    {
        $templatesPath = storage_path('app/templates/');
        $zipFileName = 'templates_documentos_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Cria diretório temp se não existir
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $templates = [
                'caf_declaracao.docx' => 'CAF - Declaração Escolha da Empresa.docx',
                'caf_formulario_casal.docx' => 'CAF - Formulário (Casal).docx',
                'caf_formulario_solteiro.docx' => 'CAF - Formulário (Solteiro).docx',
                'declaracao_incra_casal.docx' => 'Declaração INCRA (Casal).docx',
                'declaracao_incra_solteiro.docx' => 'Declaração INCRA (Solteiro).docx',
                'autodeclaracao_segurado.docx' => 'Autodeclaração do Segurado Especial.docx',
                'contrato_prestacao.docx' => 'Contrato de Prestação de Serviços.docx',
                'declaracao_posse_renda.docx' => 'Declaração de Posse e Renda.docx',
                'ficha_proposta_titular.docx' => 'Ficha Proposta PF - Titular.docx',
                'ficha_proposta_conjuge.docx' => 'Ficha Proposta PF - Cônjuge.docx',
                'termo_representacao.docx' => 'Termo de Representação.docx',
            ];

            foreach ($templates as $templateFile => $displayName) {
                $templatePath = $templatesPath . $templateFile;
                if (file_exists($templatePath)) {
                    $zip->addFile($templatePath, $displayName);
                }
            }

            // Adiciona arquivo README
            $readmeContent = $this->generateReadmeContent();
            $zip->addFromString('LEIA-ME.txt', $readmeContent);

            $zip->close();
        }

        if (!file_exists($zipPath)) {
            abort(500, 'Erro ao criar arquivo ZIP dos templates');
        }

        return response()->download($zipPath)->deleteFileAfterSend();
    }

    public function previewDocument(Request $request, string $file): Response
    {
        $filePath = storage_path('app/documents/generated/' . $file);

        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado');
        }

        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
        ]);
    }

    public function listGeneratedDocuments(): \Illuminate\Http\JsonResponse
    {
        $documentsPath = storage_path('app/documents/generated/');

        if (!is_dir($documentsPath)) {
            return response()->json(['documents' => []]);
        }

        $files = array_diff(scandir($documentsPath), ['.', '..']);
        $documents = [];

        foreach ($files as $file) {
            $filePath = $documentsPath . $file;
            if (is_file($filePath)) {
                $documents[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'created_at' => filemtime($filePath),
                    'download_url' => route('download.document', ['file' => $file]),
                    'preview_url' => route('preview.document', ['file' => $file]),
                ];
            }
        }

        // Ordena por data de criação (mais recente primeiro)
        usort($documents, function ($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });

        return response()->json(['documents' => $documents]);
    }

    public function deleteExpiredDocuments(): \Illuminate\Http\JsonResponse
    {
        $documentsPath = storage_path('app/documents/generated/');
        $deletedCount = 0;

        if (is_dir($documentsPath)) {
            $files = array_diff(scandir($documentsPath), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $documentsPath . $file;
                if (is_file($filePath) && filemtime($filePath) < strtotime('-24 hours')) {
                    unlink($filePath);
                    $deletedCount++;
                }
            }
        }

        return response()->json([
            'message' => 'Limpeza concluída',
            'deleted_count' => $deletedCount
        ]);
    }

    protected function generateReadmeContent(): string
    {
        return "SISTEMA DE GERAÇÃO DE DOCUMENTOS
========================================

Este pacote contém os templates dos documentos que podem ser gerados pelo sistema.

ESTRUTURA DOS TEMPLATES:
- Todos os templates utilizam a biblioteca PhpWord para substituição de variáveis
- As variáveis são delimitadas por chaves: {nome_variavel}
- Alguns templates são específicos para clientes casados ou solteiros

VARIÁVEIS DISPONÍVEIS:
- {nome_completo} - Nome completo do cliente
- {cpf} - CPF formatado (000.000.000-00)
- {rg} - Número do RG
- {data_nascimento} - Data de nascimento (dd/mm/aaaa)
- {estado_civil} - Estado civil
- {endereco} - Endereço completo
- {municipio} - Município
- {uf} - Unidade Federativa
- {telefone} - Telefone formatado
- {email} - E-mail
- {nome_conjuge} - Nome do cônjuge (apenas para casados)
- {cpf_conjuge} - CPF do cônjuge (apenas para casados)
- {data_atual} - Data atual
- E muitas outras...

PERSONALIZAÇÃO:
Para personalizar os templates, edite os arquivos .docx e mantenha as variáveis
entre chaves. O sistema automaticamente substituirá pelos dados do cliente.

SUPORTE:
Para dúvidas sobre customização dos templates, consulte a documentação
do sistema ou entre em contato com o desenvolvedor.

Gerado em: " . date('d/m/Y H:i:s') . "
";
    }
}
