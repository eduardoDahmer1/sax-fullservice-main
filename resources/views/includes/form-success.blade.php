@if (Session::has('success'))
                  <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ Session::get('success') }}
            </div>


@endif

@if (Session::has('unsuccess'))

            <div class="alert alert-danger alert-dismissible" style="background-color:#D03633; color:#fff;">
            <button type="button" class="close" data-dismiss="alert" style="color: #fff;">&times;</button>
                  {!! Session::get('unsuccess') !!}
            </div>
@endif

@if(session('message')==='f')
      <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{__("Credentials doesn't match !")}}
      </div>

@endif    