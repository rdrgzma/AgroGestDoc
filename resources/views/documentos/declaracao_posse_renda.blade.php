<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.6; }
        .title { text-align: center; font-weight: bold; margin-top: 20px; }
        .assinatura { margin-top: 80px; text-align: center; }
    </style>
</head>
<body>

<div class="title">AUTODECLARAÇÃO DE OCUPAÇÃO DE ÁREA DE TERRA</div>

<p>
    Eu, <strong>{{ $pessoa->nome }}</strong>, portador(a) do CPF: <strong>{{ $pessoa->cpf }}</strong>,
    residente e domiciliado(a) no endereço <strong>{{ $pessoa->endereco }} - {{ $pessoa->cidade }}/{{ $pessoa->uf }}</strong>,
    DECLARO que não sou proprietário(a) de imóvel rural, que ocupo área de terras, sem oposição de qualquer natureza,
    não superior a quatro módulos fiscais, há pelo menos doze meses ininterruptos,
    tornando o estabelecimento economicamente produtivo para fins de sustento próprio,
    sustento da minha família e de geração de renda.
</p>

<p>
    DECLARO, para todos os fins de direito e sob as penas da Lei, serem verdadeiras as informações prestadas nesta Declaração,
    ciente de que a prestação de informação falsa e/ou apresentação de documento falso poderá incorrer nas penas de crime
    previstas nos arts. 297, 298 e 299 do Código Penal - Decreto Lei nº 2.848, de 7 de dezembro de 1940.
</p>

<p class="title">AUTODECLARAÇÃO DE RENDA FAMILIAR DA UFPA</p>

<p>
    Eu, <strong>{{ $pessoa->nome }}</strong>, CPF: <strong>{{ $pessoa->cpf }}</strong>,
    declaro que a renda bruta da Unidade Familiar de Produção Agrária é de
    <strong>R$ {{ number_format($renda['propria'], 2, ',', '.') }}</strong>,
    oriunda das atividades econômicas do estabelecimento nos últimos 12 meses.
</p>

<p>
    A renda obtida fora do estabelecimento é de <strong>R$ {{ number_format($renda['externa'], 2, ',', '.') }}</strong>,
    composta por benefícios sociais ou rendimentos dos membros da família.
</p>

<p>
    {{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.
</p>

<div class="assinatura">
    ___________________________________________<br>
    {{ $pessoa->nome }}
</div>
</body>
</html>

