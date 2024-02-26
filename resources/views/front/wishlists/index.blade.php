@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
<style>
    a.wishlist:hover {
        text-decoration: underline;
    }
</style>

<div class="modal fade" id="createWishlist" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content pt-3">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Create wishlist')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('user-wishlists.store')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label">{{__('Name')}}:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Name')}}">
                    </div>
                    <div class="row justify-content-end px-3">
                        <button type="button" class="btn btn-style-1 px-2 py-1 mr-3" data-dismiss="modal"><small>{{__('Cancel')}}</small></button>
                        <button type="submit" class="btn btn-style-1 px-2 py-1"><small>{{__('Create')}}</small></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5 mb-5">
    <div class="row px-5">
        <div class="col-12 d-flex justify-content-between py-3">
            <h1 class="h3 mb-3">
                {{__('Your wishlists')}}
            </h1>
            <button type="button" class="btn btn-style-1 px-3 py-0" data-toggle="modal" data-target="#createWishlist">
                <small>{{__('Create wishlist')}}</small>
            </button>
        </div>
        @foreach ($wishlistsGroup as $group)
            <a class="col-12 py-3 px-2 aling-center wishlist border-top" href="{{route('user-wishlists.show', $group)}}">
                <div class="row">
                    <div class="col-md-1 col-3">
                        @if ($group->wishlists()->count())
                            <img src="{{$group->wishlists()->first()->product->image}}">
                        @endif
                    </div>
                    <div class="col-md-10 col-8 d-flex align-items-center pl-3">
                        <div>
                            {{$group->name}}
                            <small class="d-block">{{$group->is_public ? __('Public') : __('Private')}}</small>
                        </div>
                    </div>
                    <div class="col-1 d-flex flex-column justify-content-center" style="justify-content: space-evenly">
                        <form action="{{route('user-wishlists.destroy', $group->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-light border">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div>
        {{$wishlistsGroup->links()}}
    </div>
</div>
@endsection