<div class="col-sm-12">
    <div class="row">
        @foreach ($tcategory->team_members as $speople)
            <div class="col-lg-6">
                <div class="list-group-item d-flex justify-content-between align-items-center"
                    style="border: 0ch; text-align: center">
                    <img class="card-img-left"
                        src="{{ $speople->photo ? asset('storage/images/team_member/' . $speople->photo) : asset('assets/images/noimage.png') }}"
                        style="max-width: 70px; max-height: 70px; border-radius: 30px;">
                    <div class="container">
                        <div class="row" style="justify-content: center;">
                            <p>{{ $speople->name }}</p>
                        </div>
                        <div class="row" style="justify-content: center;">
                            <p style="20px !important;">
                                <i class="fab fa-whatsapp" aria-hidden="true" style="color: green"></i>
                                <a href="https://api.whatsapp.com/send?1=pt_BR&phone={{ $speople->whatsapp }}&text=Ol%C3%A1,%20estava%20olhando%20o%20site%20da%20empresa%20e%20gostaria%20de%20mais%20informa%C3%A7%C3%B5es"
                                    target="_blank">
                                    {{ $speople->whatsapp }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
