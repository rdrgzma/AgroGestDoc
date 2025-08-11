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

<h3>FICHA PROPOSTA – PESSOA FÍSICA (PARTE 2)</h3>

<table border="1">
    <tr>
        <td><strong>Nome do Cônjuge</strong></td>
        <td>{{ $conjuge->nome ?? 'NÃO INFORMADO' }}</td>
    </tr>
    <tr>
        <td><strong>CPF do Cônjuge</strong></td>
        <td>{{ $conjuge->cpf ?? '---' }}</td>
    </tr>
    <tr>
        <td><strong>Atividade Desenvolvida pelo Casal</strong></td>
        <td>{{ $atividade ?? '---' }}</td>
    </tr>
    <tr>
        <td><strong>Renda Familiar Estimada</strong></td>
        <td>R$ {{ number_format($renda['total'], 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Endereço do Imóvel Rural</strong></td>
        <td>{{ $ufpa->endereco ?? $pessoa->endereco }}</td>
    </tr>
</table>

<p style="margin-top: 30px;">Declaro, sob as penas da lei, que as informações acima são verdadeiras.</p>

<p style="margin-top: 40px;">{{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.</p>

<p style="margin-top: 60px; text-align: center;">
    ___________________________________________<br>
    {{ $pessoa->nome }}
</p>

<p style="margin-top: 40px; text-align: center;">
    ___________________________________________<br>
    {{ $conjuge->nome ?? 'Assinatura do Cônjuge' }}
</p>

</body>
</html>

