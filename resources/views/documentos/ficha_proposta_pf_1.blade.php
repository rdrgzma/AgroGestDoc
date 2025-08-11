<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 6px; vertical-align: top; }
        h3 { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<h3>FICHA PROPOSTA – PESSOA FÍSICA (PARTE 1)</h3>

<table border="1">
    <tr>
        <td><strong>Nome do Beneficiário</strong></td>
        <td>{{ $pessoa->nome }}</td>
    </tr>
    <tr>
        <td><strong>CPF</strong></td>
        <td>{{ $pessoa->cpf }}</td>
    </tr>
    <tr>
        <td><strong>RG</strong></td>
        <td>{{ $pessoa->rg }}</td>
    </tr>
    <tr>
        <td><strong>Endereço</strong></td>
        <td>{{ $pessoa->endereco }} - {{ $pessoa->bairro }} - {{ $pessoa->cidade }}/{{ $pessoa->uf }}</td>
    </tr>
    <tr>
        <td><strong>Telefone</strong></td>
        <td>{{ $pessoa->telefone }}</td>
    </tr>
    <tr>
        <td><strong>Data de Nascimento</strong></td>
        <td>{{ \Carbon\Carbon::parse($pessoa->data_nascimento)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td><strong>Estado Civil</strong></td>
        <td>{{ ucfirst($pessoa->estado_civil) }}</td>
    </tr>
    <tr>
        <td><strong>Nome da Mãe</strong></td>
        <td>{{ $pessoa->nome_mae }}</td>
    </tr>
</table>

<p style="margin-top: 30px;">Declaro, sob as penas da lei, que as informações acima são verdadeiras.</p>

<p style="margin-top: 40px;">{{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.</p>

<p style="margin-top: 60px; text-align: center;">
    ___________________________________________<br>
    {{ $pessoa->nome }}
</p>

</body>
</html>
