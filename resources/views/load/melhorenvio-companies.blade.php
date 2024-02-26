@if(Auth::guard('admin')->check())

@foreach ($melhorenvio_companies as $company)    
<div class="row justify-content-center">
  <div class="col-lg-3">
    <div class="left-area">
      <img src="{{ $company->picture }}" alt="{{ $company->name }}" style="max-height:50px; max-width:180px">      
    </div>
  </div>
  <div class="col-lg-6">
    <div class="row justify-content-left">
      <h4 class="heading ml-3">{{ $company->name }}</h4>
      @foreach($company->services as $service)
      <div class="col-lg-12 d-flex justify-content-between">
          <label class="control-label" for="service{{$service->id}}">{{$service->name}} |
              {{($service->type == 'express') ? __('Express') : __('Normal')}}</label>
          <label class="switch">
              <input type="checkbox" name="selected_services[]" id="service{{$service->id}}"
                  value="{{$service->id}}"
                  {{in_array($service->id, $admstore->melhorenvio->selected_services)? 'checked' : ''}}>
              <span class="slider round"></span>
          </label>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endforeach

@endif