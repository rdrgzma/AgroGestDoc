<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.6; }
        .center { text-align: center; margin-top: 30px; }
        .assinatura { margin-top: 80px; text-align: center; }
    </style>
</head>
<body>
<h3 class="center">DECLARAÇÃO</h3>

<p>
    Eu, <strong>{{ $pessoa->nome }}</strong>, portador(a) da carteira de identidade nº <strong>{{ $pessoa->rg }}</strong>,
    CPF nº <strong>{{ $pessoa->cpf }}</strong>, morador(a) do endereço <strong>{{ $pessoa->endereco }} - {{ $pessoa->cidade }}/{{ $pessoa->uf }}</strong>,
    declaro que escolhi a empresa <strong>M. DE JESUS DA CONCEICAO PINHEIRO LTDA</strong>,
    CNPJ nº <strong>20.549.404/0001-46</strong>, para prestar assistência técnica e elaborar proposta de Projetos ao PRONAF.
    Assim como, autorizo a referida empresa a solicitar e retirar, junto ao INCRA – Instituto Nacional de Colonização e Reforma Agrária,
    os documentos referentes ao CAF – Cadastro Nacional da Agricultura Familiar (Extrato-CAF, Carteira-CAF e CAF-PRONAF).
</p>

<p class="center">
    {{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.
</p>

<div class="assinatura">
    ___________________________________________<br>
    {{ $pessoa->nome }}
</div>
</body>
</html>
