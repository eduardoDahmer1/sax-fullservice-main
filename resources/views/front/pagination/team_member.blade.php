@foreach($team_member_categories as $category)
<hr>
<h5>{{$category->name}}</h5>
<hr>
@php $team_members = DB::table('team_members')->where('category_id', $category->id)->get(); @endphp
<div class="row">
    @foreach($team_members as $member)
    <div class="col-lg-3">
        <div class="blog-box" style="min-height: 387px;">
            <div style="max-height: 200px;">
                <div class="img" style="text-align: center; min-height: 200px;">
                    <img src="{{ $member->photo ? asset('storage/images/team_member/'.$member->photo):asset('assets/images/noimage.png') }}"
                        class="img-fluid" alt="" style="max-height: 200px;">
                </div>
            </div>
            <div class="details">
                <p class="name_team"><span>{{substr(strip_tags($member->name),0,120)}}</span></p>
                <ul style="text-align: center;">
                    @if($member->whatsapp)
                    <li>
                        <p class="blog-text">
                            <i class="fab fa-whatsapp" aria-hidden="true" style="color: green"></i>
                            <a class="team_contacts_wpp"
                                href="https://api.whatsapp.com/send?1=pt_BR&phone={{$member->whatsapp}}&text="
                                target="_blank">{{substr(strip_tags($member->whatsapp),0,120)}}</a>
                        </p>
                    </li>
                    @endif
                    @if($member->skype)
                    <li>
                        <p class="blog-text"><i class="fab fa-skype" aria-hidden="true" style="color: #0078d4"></i>
                            <a class="team_contacts_skype" href="skype:{{substr(strip_tags($member->skype),0,120)}}">
                                {{substr(strip_tags($member->skype),0,120)}}</a>
                        </p>
                    </li>
                    @endif
                    @if($member->email)
                    <li>
                        <p class="blog-text"><i class="fa fa-envelope" aria-hidden="true" style="color: #d93025"></i>
                            <a class="team_contacts_email" href="{{substr(strip_tags($member->email),0,120)}}">
                                {{substr(strip_tags($member->email),0,120)}}</a>
                        </p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endforeach
<div>
    {!! $team_member_categories->links() !!}
</div>
