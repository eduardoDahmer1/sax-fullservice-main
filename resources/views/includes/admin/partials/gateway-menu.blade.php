<ul class="nav flex-column">
    @if(config("gateways.bancard"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/bancard') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/bancard') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-bancard') }}">Bancard</a>
    </li>
    @endif
    @if(config("gateways.mercado_pago"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/mercadopago') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/mercadopago') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-mercadopago') }}">Mercado Pago</a>
    </li>
    @endif
    @if(config("gateways.pagarme"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/pagarme') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/pagarme') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-pagarme') }}">Pagarme</a>
    </li>
    @endif
    @if(config("gateways.pagseguro"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/pagseguro') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/pagseguro') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-pagseguro') }}">Pagseguro</a>
    </li>
    @endif
    @if(config("gateways.cielo"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/cielo') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/cielo') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-cielo') }}">Cielo</a>
    </li>
    @endif
    @if(config("gateways.pagopar"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/pagopar') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/pagopar') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-pagopar') }}">Pagopar</a>
    </li>
    @endif
    @if(config("gateways.rede"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/rede') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/rede') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-rede') }}">Rede</a>
    </li>
    @endif
    @if(config("gateways.paghiper") || config("gateways.paghiper_pix"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/paghiper') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/paghiper') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-paghiper') }}">PagHiper / PIX</a>
    @endif
    @if(config("gateways.paypal"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/paypal') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/paypal') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-paypal') }}">Pay Pal</a>
    </li>
    @endif
    @if(config("gateways.pay42"))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/payment-informations/gateway/pay42') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/payment-informations/gateway/pay42') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-payments-pay42') }}">Pay42</a>
    </li>
    @endif
</ul>