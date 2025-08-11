<?ph<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; }
    h3 { text-align: center; margin-top: 30px; }
    .assinaturas { margin-top: 60px; text-align: center; }
  </style>
</head>
<body>

<h3>DECLARAÇÃO</h3>

<p>
Nós, <strong>{{ $pessoa->nome }}</strong>, CPF nº <strong>{{ $pessoa->cpf }}</strong>, e
<strong>{{ $conjuge->nome }}</strong>, CPF nº <strong>{{ $conjuge->cpf }}</strong>,
residentes no endereço <strong>{{ $pessoa->endereco }}, {{ $pessoa->cidade }}/{{ $pessoa->uf }}</strong>,
declaramos que exercemos atividades produtivas rurais na área ocupada situada em {{ $ufpa->assentamento }},
município de {{ $ufpa->municipio }}/{{ $ufpa->estado }}, com coordenadas geográficas:
</p>

<p>
    <strong>Latitude:</strong> {{ $ufpa->latitude }}<br>
    <strong>Longitude:</strong> {{ $ufpa->longitude }}
</p>

<p>
    A área é economicamente produtiva e destinada à agricultura familiar, conforme definido pela Lei nº 11.326/2006.
</p>

<p>
    Declaramos, sob as penas da lei, que as informações acima são verdadeiras.
</p>

<p>{{ $pessoa->cidade }}/{{ $pessoa->uf }}, {{ now()->format('d \d\e F \d\e Y') }}.</p>

<div class="assinaturas">
    ___________________________________________<br>
    {{ $pessoa->nome }}

    <br><br>

    ___________________________________________<br>
    {{ $conjuge->nome }}
</div>

</body>
</html>
p
