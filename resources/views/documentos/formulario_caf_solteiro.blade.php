<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 4px; vertical-align: top; }
    </style>
</head>
<body>

<h2>Formulário de Solicitação de Cadastro Nacional da Agricultura Familiar — CAF</h2>
<h3>Declarante</h3>

<p><strong>Projeto de Assentamento:</strong> {{ $ufpa->assentamento }} &nbsp;&nbsp;
    <strong>Município:</strong> {{ $ufpa->municipio }}</p>

<p><strong>Nome do Declarante:</strong> {{ $pessoa->nome }} &nbsp;&nbsp;
    <strong>CPF:</strong> {{ $pessoa->cpf }}</p>
<p><strong>Apelido:</strong> {{ $pessoa->apelido ?? '-' }} &nbsp;&nbsp;
    <strong>Estado Civil:</strong> {{ ucfirst($pessoa->estado_civil) }}</p>
<p><strong>Cor/Raça:</strong> {{ $pessoa->raca ?? '-' }} &nbsp;&nbsp;
    <strong>Telefone:</strong> {{ $pessoa->telefone }} &nbsp;&nbsp;
    <strong>E-mail:</strong> {{ $pessoa->email ?? '-' }}</p>

<hr>
<h3>Área de Produção</h3>
<table>
    <tr>
        <td><strong>Tipo:</strong> Terra</td>
        <td><strong>Área:</strong> {{ $ufpa->area_total ?? '---' }} ha</td>
        <td><strong>UF:</strong> {{ $ufpa->estado }}</td>
        <td><strong>Município:</strong> {{ $ufpa->municipio }}</td>
    </tr>
    <tr>
        <td><strong>Lâmina d’água:</strong> {{ $ufpa->lamina_agua ?? '-' }} ha</td>
        <td><strong>Tanque:</strong> {{ $ufpa->tanque ?? '-' }} m³</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Latitude:</strong> {{ $ufpa->latitude }}</td>
        <td colspan="2"><strong>Longitude:</strong> {{ $ufpa->longitude }}</td>
    </tr>
</table>

<hr>
<h3>Informações de Renda</h3>
<p><strong>Renda produtiva no estabelecimento:</strong> R$ {{ number_format($renda['propria'], 2, ',', '.') }}</p>
<p><strong>Renda fora do estabelecimento:</strong> R$ {{ number_format($renda['externa'], 2, ',', '.') }}</p>
<p><strong>Benefícios sociais e previdência:</strong> R$ {{ number_format($renda['beneficios'], 2, ',', '.') }}</p>

<hr>
<h3>Endereço da UFPA</h3>
<p>{{ $pessoa->endereco }} - {{ $pessoa->bairro }} - {{ $pessoa->cidade }}/{{ $pessoa->uf }}</p>
<p>CEP: {{ $pessoa->cep }}</p>

<p style="margin-top: 40px;">{{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.</p>

<p style="margin-top: 60px;">_________________________________________<br>
    {{ $pessoa->nome }} (Declarante)</p>

</body>
</html>

