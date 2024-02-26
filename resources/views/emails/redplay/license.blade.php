@component('mail::message')
# Redplay App - Código de Licença

<h3>Seu código de licença é pessoal e intransferível. Não o envie para ninguém.</h3>

Número do Pedido: <b>{{ $order->order_number }}</b>
<br>
<br>
Produto: <b>{{ $license->product->name }}</b>
<br>
<br>
@component('mail::table')
|Login | Senha | Código |
|:-------------:|:-------------:|:--------:|
| {{ $license->login ?? 'Inexistente' }}|{{ $license->password ?? 'Inexistente' }}|{{ $license->code ?? 'Inexistente' }}|
@endcomponent

Obrigado,<br>
Equipe Redplay
@endcomponent
