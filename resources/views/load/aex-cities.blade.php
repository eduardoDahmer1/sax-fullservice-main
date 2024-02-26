@if(Auth::guard('admin')->check())

    <option value="">{{ __('Select City') }}</option>
    @foreach($aex_cities as $city)
    <option {{$city->codigo_ciudad == $admstore->aex_origin ? "selected":""}}
        value="{{ $city->codigo_ciudad }}">{{$city->denominacion}} - {{$city->departamento_denominacion}}</option>
    @endforeach

@else 

    <option value="">{{ __('Select City') }}</option>
    @foreach($aex_cities as $city)
    <option
        value="{{ $city->codigo_ciudad }}">{{$city->denominacion}} - {{$city->departamento_denominacion}}</option>
    @endforeach

@endif