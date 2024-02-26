      <div class="alert alert-danger validation" style="display: none;">
      <button type="button" class="close alert-close"><span>×</span></button>
            <ul class="text-left">
            </ul>
      </div>
      @if(session('error'))
      <div class="alert alert-danger validation">
            <button type="button" class="close alert-close"><span>×</span></button>
                  <p class="text-left">{{ session('error') }}</p> 
            </div>
      @endif
      @if(session('errors'))
      <div class="alert alert-danger validation">
            <button type="button" class="close alert-close"><span>×</span></button>
            @foreach(session('errors') as $error)
                  <p class="text-left">{{ $error }}</p> 
            @endforeach
            </div>
      @endif