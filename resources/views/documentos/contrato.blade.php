<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2, h3 { text-align: center; }
    </style>
</head>
<body>
<h2>CONTRATO DE PRESTAÇÃO DE SERVIÇOS</h2>
<p>
    Celebram entre si a empresa <strong>M. DE JESUS DA CONCEICAO PINHEIRO LTDA</strong>
    e o(a) Sr(a). <strong>{{ $pessoa->nome }}</strong>, CPF {{ $pessoa->cpf }},
    residente em {{ $pessoa->endereco }}, {{ $pessoa->cidade }}/{{ $pessoa->uf }},
    o presente contrato de assistência técnica.
</p>

<p><strong>CLÁUSULA PRIMEIRA - DO OBJETO</strong></p>
<p>
    Constitui objeto deste contrato a elaboração de projeto de viabilidade econômica
    para obtenção de crédito junto ao PRONAF.
</p>

<p><strong>CLÁUSULA SEGUNDA - DO PRAZO</strong></p>
<p>Vigência de 36 meses a partir da assinatura.</p>

<p style="margin-top: 50px;">Macapá, {{ now()->format('d/m/Y') }}</p>

<p>_______________________________________<br>
    {{ $pessoa->nome }} - CONTRATANTE
</p>
</body>
</html>

