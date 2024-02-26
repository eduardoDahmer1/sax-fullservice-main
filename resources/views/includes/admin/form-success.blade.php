      <div class="alert alert-success validation" style="display: none;">
      <button type="button" class="close alert-close"><span>×</span></button>
            <p class="text-left"></p> 
      </div>
      @if(session('success'))
      <div class="alert alert-success validation">
            <button type="button" class="close alert-close"><span>×</span></button>
                  <p class="text-left">{{ session('success') }}</p> 
            </div>
      @endif