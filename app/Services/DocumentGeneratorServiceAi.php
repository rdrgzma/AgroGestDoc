<?php

namespace App\Services;

use App\Models\Cliente;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;

class DocumentGeneratorServiceAi
{
    protected array $documentTypes = [
        'caf_declaracao' => [
            'name' => 'CAF - Declaração Escolha da Empresa',
            'template' => 'templates/1. CAF - Declaração - escolha da Empresa (modelo) OK.docx',
            'required_for' => 'all'
        ],
        'caf_formulario_casal' => [
            'name' => 'CAF - Formulário (Casal)',
            'template' => 'templates/1. CAF - FORMULÁRIO CAF & ANEXO I (modelo) - Casal OK.docx',
            'required_for' => 'married'
        ],
        'caf_formulario_solteiro' => [
            'name' => 'CAF - Formulário (Solteiro)',
            'template' => 'templates/1. CAF - FORMULÁRIO CAF & ANEXO I (modelo) - Solteiro OK.docx',
            'required_for' => 'single'
        ],
        'declaracao_incra_casal' => [
            'name' => 'Declaração INCRA (Casal)',
            'template' => 'templates/2 D - DECLARAÇÃO (ANEXO XII DA IN 992019 - INCRA) - casal OK.docx',
            'required_for' => 'married'
        ],
        'declaracao_incra_solteiro' => [
            'name' => 'Declaração INCRA (Solteiro)',
            'template' => 'templates/2 D - DECLARAÇÃO (ANEXO XII DA IN 992019 - INCRA) - solteiro OK.docx',
            'required_for' => 'single'
        ],
        'autodeclaracao_segurado' => [
            'name' => 'Autodeclaração do Segurado Especial Rural',
            'template' => 'templates/AUTODECLARAÇÃO DO SEGURADO ESPECIAL RURAL-1.docx',
            'required_for' => 'all'
        ],
        'contrato_prestacao' => [
            'name' => 'Contrato de Prestação de Serviços',
            'template' => 'templates/Contrato de Prestação de Serviços (modelo) OK.docx',
            'required_for' => 'all'
        ],
        'declaracao_posse_renda' => [
            'name' => 'Declaração de Posse e Renda',
            'template' => 'templates/Declaração POSSE e RENDA.docx',
            'required_for' => 'with_income'
        ],
        'ficha_proposta_titular' => [
            'name' => 'Ficha Proposta PF - Titular',
            'template' => 'FICHA PROPOSTA PF l (modelo) OK.docx',
            'required_for' => 'all'
        ],
        'ficha_proposta_conjuge' => [
            'name' => 'Ficha Proposta PF - Cônjuge',
            'template' => 'templates/ficha_proposta_conjuge.docx',
            'required_for' => 'married'
        ],
        'termo_representacao' => [
            'name' => 'Termo de Representação',
            'template' => 'templates/TERMO DE REPRESENTAÇÃO (modelo).docx',
            'required_for' => 'all'
        ]
    ];

    public function generateDocument(Cliente $cliente, string $documentType): string
    {
        $templatePath = storage_path('app/' . $this->documentTypes[$documentType]['template']);

        if (!file_exists($templatePath)) {
            throw new \Exception("Template não encontrado: {$templatePath}");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Aplica as variáveis do cliente no template
        $this->applyClientVariables($templateProcessor, $cliente, $documentType);

        // Gera nome único para o arquivo
        $fileName = $this->generateFileName($cliente, $documentType);
        $outputPath = storage_path('app/documents/generated/' . $fileName);

        // Cria diretório se não existir
        if (!is_dir(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        $templateProcessor->saveAs($outputPath);

        return $outputPath;
    }

    public function generateAllDocuments(Cliente $cliente): string
    {
        $generatedFiles = [];
        $clientCondition = $this->getClientCondition($cliente);

        foreach ($this->documentTypes as $type => $config) {
            if ($this->shouldGenerateDocument($config['required_for'], $clientCondition)) {
                try {
                    $filePath = $this->generateDocument($cliente, $type);
                    $generatedFiles[] = $filePath;
                } catch (\Exception $e) {
                    \Log::error("Erro ao gerar documento {$type} para cliente {$cliente->id}: " . $e->getMessage());
                }
            }
        }

        // Cria um ZIP com todos os documentos
        return $this->createZipFile($cliente, $generatedFiles);
    }

    protected function applyClientVariables(TemplateProcessor $templateProcessor, Cliente $cliente, string $documentType): void
    {
        // Variáveis básicas do cliente
        $variables = [
            'nome_completo' => $cliente->nome_completo ?? '',
            'cpf' => $this->formatCpf($cliente->cpf ?? ''),
            'rg' => $cliente->rg ?? '',
            'orgao_expedidor' => $cliente->orgao_expedidor ?? '',
            'data_nascimento' => $cliente->data_nascimento ? Carbon::parse($cliente->data_nascimento)->format('d/m/Y') : '',
            'local_nascimento' => $cliente->local_nascimento ?? '',
            'estado_civil' => $cliente->estado_civil ?? '',
            'endereco' => $cliente->endereco ?? '',
            'municipio' => $cliente->municipio ?? '',
            'uf' => $cliente->uf ?? '',
            'cep' => $this->formatCep($cliente->cep ?? ''),
            'telefone' => $this->formatTelefone($cliente->telefone ?? ''),
            'email' => $cliente->email ?? '',
            'nome_mae' => $cliente->nome_mae ?? '',
            'projeto_assentamento' => $cliente->projeto_assentamento ?? '',
            'codigo_sipra' => $cliente->codigo_sipra ?? '',
            'area_total' => $cliente->area_total ?? '',
            'area_explorada' => $cliente->area_explorada ?? '',
            'renda_bruta_estabelecimento' => $this->formatMoney($cliente->renda_bruta_estabelecimento ?? 0),
            'renda_fora_estabelecimento' => $this->formatMoney($cliente->renda_fora_estabelecimento ?? 0),
            'beneficios_sociais' => $this->formatMoney($cliente->beneficios_sociais ?? 0),
            'coordenada_latitude' => $cliente->coordenada_latitude ?? '',
            'coordenada_longitude' => $cliente->coordenada_longitude ?? '',
            'data_atual' => Carbon::now()->format('d/m/Y'),
            'dia_atual' => Carbon::now()->format('d'),
            'mes_atual' => $this->getMonthName(Carbon::now()->month),
            'ano_atual' => Carbon::now()->format('Y'),
        ];

        // Variáveis específicas para cônjuge (se casado)
        if ($cliente->estado_civil === 'casado' || $cliente->estado_civil === 'união estável') {
            $variables = array_merge($variables, [
                'nome_conjuge' => $cliente->nome_conjuge ?? '',
                'cpf_conjuge' => $this->formatCpf($cliente->cpf_conjuge ?? ''),
                'rg_conjuge' => $cliente->rg_conjuge ?? '',
                'data_nascimento_conjuge' => $cliente->data_nascimento_conjuge ?
                    Carbon::parse($cliente->data_nascimento_conjuge)->format('d/m/Y') : '',
                'local_nascimento_conjuge' => $cliente->local_nascimento_conjuge ?? '',
            ]);
        }

        // Variáveis específicas por tipo de documento
        $this->addDocumentSpecificVariables($variables, $cliente, $documentType);

        // Aplica todas as variáveis no template
        foreach ($variables as $variable => $value) {
            try {
                $templateProcessor->setValue($variable, $value);
            } catch (\Exception $e) {
                \Log::warning("Variável {$variable} não encontrada no template {$documentType}");
            }
        }
    }

    protected function addDocumentSpecificVariables(array &$variables, Cliente $cliente, string $documentType): void
    {
        switch ($documentType) {
            case 'caf_formulario_casal':
            case 'caf_formulario_solteiro':
                $variables['tipo_area'] = $cliente->tipo_area ?? 'Lâmina de água';
                $variables['atividade_principal'] = $cliente->atividade_principal ?? 'Pescador artesanal';
                break;

            case 'contrato_prestacao':
                $variables['numero_contrato'] = $this->generateContractNumber();
                $variables['banco_agencia'] = $cliente->banco_agencia ?? 'Macapá';
                $variables['conta_corrente'] = $cliente->conta_corrente ?? '';
                break;

            case 'autodeclaracao_segurado':
                $variables['periodo_atividade_inicio'] = $cliente->periodo_atividade_inicio ?
                    Carbon::parse($cliente->periodo_atividade_inicio)->format('d/m/Y') : '';
                $variables['periodo_atividade_fim'] = $cliente->periodo_atividade_fim ?
                    Carbon::parse($cliente->periodo_atividade_fim)->format('d/m/Y') : '';
                $variables['condicao_imovel'] = $cliente->condicao_imovel ?? 'Posseiro';
                break;
        }
    }

    protected function getClientCondition(Cliente $cliente): string
    {
        $hasIncome = ($cliente->renda_bruta_estabelecimento ?? 0) > 0 ||
                    ($cliente->renda_fora_estabelecimento ?? 0) > 0;

        $isMarried = in_array($cliente->estado_civil, ['casado', 'união estável']);

        if ($isMarried) {
            return $hasIncome ? 'married_with_income' : 'married';
        }

        return $hasIncome ? 'single_with_income' : 'single';
    }

    protected function shouldGenerateDocument(string $requiredFor, string $clientCondition): bool
    {
        return match ($requiredFor) {
            'all' => true,
            'married' => in_array($clientCondition, ['married', 'married_with_income']),
            'single' => in_array($clientCondition, ['single', 'single_with_income']),
            'with_income' => in_array($clientCondition, ['married_with_income', 'single_with_income']),
            default => false
        };
    }

    protected function createZipFile(Cliente $cliente, array $filePaths): string
    {
        $zipFileName = $this->generateZipFileName($cliente);
        $zipPath = storage_path('app/documents/generated/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($filePaths as $filePath) {
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        }

        return $zipPath;
    }

    protected function generateFileName(Cliente $cliente, string $documentType): string
    {
        $documentName = $this->documentTypes[$documentType]['name'];
        $clientName = Str::slug($cliente->nome_completo ?? 'cliente');
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        return "{$clientName}_{$documentType}_{$timestamp}.docx";
    }

    protected function generateZipFileName(Cliente $cliente): string
    {
        $clientName = Str::slug($cliente->nome_completo ?? 'cliente');
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        return "documentos_{$clientName}_{$timestamp}.zip";
    }

    protected function generateContractNumber(): string
    {
        return str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . '/' . Carbon::now()->format('Y');
    }

    protected function formatCpf(string $cpf): string
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    protected function formatCep(string $cep): string
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    protected function formatTelefone(string $telefone): string
    {
        if (strlen($telefone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        }
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
    }

    protected function formatMoney(float $value): string
    {
        return number_format($value, 2, ',', '.');
    }

    protected function getMonthName(int $month): string
    {
        $months = [
            1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
            5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
            9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
        ];

        return $months[$month] ?? '';
    }

    public function getAvailableDocuments(Cliente $cliente): array
    {
        $clientCondition = $this->getClientCondition($cliente);
        $availableDocuments = [];

        foreach ($this->documentTypes as $type => $config) {
            if ($this->shouldGenerateDocument($config['required_for'], $clientCondition)) {
                $availableDocuments[$type] = $config['name'];
            }
        }

        return $availableDocuments;
    }

    public function downloadDocument(string $filePath): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->download($filePath)->deleteFileAfterSend();
    }
}
