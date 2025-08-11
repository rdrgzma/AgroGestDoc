<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.7; }
        h2 { text-align: center; margin-bottom: 20px; }
        p { text-align: justify; }
    </style>
</head>
<body>

<h2>CONTRATO DE PRESTAÇÃO DE SERVIÇOS</h2>

<p>
    Pelo presente instrumento particular de contrato de prestação de serviços, de um lado
    <strong>M. DE JESUS DA CONCEICAO PINHEIRO LTDA</strong>, com sede na cidade de {{ $empresa->cidade }}, CNPJ {{ $empresa->cnpj }},
    doravante denominada CONTRATADA; e de outro lado o(a) Sr(a). <strong>{{ $pessoa->nome }}</strong>, CPF {{ $pessoa->cpf }},
    residente à <strong>{{ $pessoa->endereco }} - {{ $pessoa->bairro }}</strong>, na cidade de {{ $pessoa->cidade }}/{{ $pessoa->uf }},
    doravante denominado(a) CONTRATANTE, têm justo e contratado o seguinte:
</p>

<p><strong>CLÁUSULA PRIMEIRA - DO OBJETO</strong></p>
<p>
    Constitui objeto deste contrato a prestação de serviço de assistência técnica especializada
    para a elaboração de projetos produtivos no âmbito do PRONAF – Programa Nacional de Fortalecimento da Agricultura Familiar.
</p>

<p><strong>CLÁUSULA SEGUNDA - DAS CONDIÇÕES</strong></p>
<p>
    O serviço será prestado pela CONTRATADA por meio de equipe técnica habilitada, a partir da assinatura deste instrumento,
    mediante autorização expressa do contratante, com acompanhamento e visitas técnicas, conforme necessário.
</p>

<p><strong>CLÁUSULA TERCEIRA - DO PRAZO</strong></p>
<p>
    O presente contrato terá vigência de 36 (trinta e seis) meses, contados a partir da data de assinatura.
</p>

<p><strong>CLÁUSULA QUARTA - DA REMUNERAÇÃO</strong></p>
<p>
    O pagamento será efetuado conforme condições definidas entre as partes, podendo incluir percentuais sobre o valor do financiamento
    ou valor fixo acordado em documento anexo.
</p>

<p style="margin-top: 40px;">{{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.</p>

<p style="margin-top: 60px; text-align: center;">
    ___________________________________________<br>
    {{ $pessoa->nome }} - CONTRATANTE
</p>

<p style="margin-top: 40px; text-align: center;">
    ___________________________________________<br>
    {{ $empresa->representante }} - CONTRATADA
</p>

</body>
</html>

