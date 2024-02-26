@extends('layouts.load')

@section('content')
<div class="top-area">
    <div class="row">
        <div class="col-sm-6 text-right">
            <div class="upload-img-btn">
                <form method="POST" enctype="multipart/form-data" id="form-gallery">
                    {{ csrf_field() }}
                    <input type="hidden" id="cid" name="category_id" value="{{ $data->id }}">
                    <input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*" multiple>
                    <label for="image-upload" id="cat_gallery"><i class="icofont-upload-alt"></i>{{ __("Upload File")
                        }}</label>
                </form>
            </div>
        </div>
        <div class="col-sm-6">
            <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i>
                {{ __("Done") }}</a>
        </div>
        <div class="col-sm-12 text-center">(
            <small>{{ __("You can upload multiple Images") }}.</small> )
        </div>
    </div>
</div>
<div class="gallery-images">
    <div class="selected-image">
        <div class="row">
            <div>
                <span>

                </span>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var cid = $('#cid').val();
        $('.selected-image .row').html('');
        $('.selected-image .row').html('');
        $.ajax({
            type: 'GET',
            url: '{{ route("admin-categorygallery-show") }}',
            data: {
                id: cid
            },
            success: function(data) {
                if (data[0] == 0) {
                    $('.selected-image .row').addClass('justify-content-center');
                    $('.selected-image .row').html('<h3>{{ __("No Images Found.") }}</h3>');
                } else {
                    if (data[2]) {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        for (var k in data[2]) {
                            $('.selected-image .row').append('<div class="col-sm-4">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + data[2][k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + data[2][k] + '" target="_blank">' +
                                '<img src="' + data[2][k] + '" alt="gallery image">' +
                                '<div>' + data[2][k]['id'] + '</div>' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    } else {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function(el) {
                            return el
                        });
                        for (var l in arr) {
                            $('.selected-image .row').append('<div class="col-sm-4">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[l]['id'] + '">' +
                                '</span>' +
                                '<div class="gallery-img-id"><span>' + arr[l]['id'] +
                                '</span></div>' +
                                '<a href="' + '{{asset("storage/images/galleries")."/"}}' + arr[
                                    l][
                                    'customizable_gallery'
                                ] + '" target="_blank">' +
                                '<img src="' + '{{asset("storage/images/galleries")."/"}}' + arr[
                                    l][
                                    'customizable_gallery'
                                ] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    $(document).on('click', '.remove-img', function() {
        var id = $(this).find('input[type=hidden]').val();
        $(this).parent().parent().remove();
        $.ajax({
            type: 'GET',
            url: '{{ route("admin-categorygallery-delete") }}',
            data: {
                id: id
            }
        });
    });
</script>

<script>
    // spoof the actual file input
    $(document).on('click', '#cat_gallery', function() {
        $('#uploadgallery').click();
    });

    // when the file input changes, submit the upload
    $('#uploadgallery').change(function() {
        $('#form-gallery').submit();
    });
</script>

<script>
    $(document).on('submit', '#form-gallery', function() {
        $.ajax({
            url: '{{ route("admin-categorygallery-store") }}',
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    $('.selected-image .row').removeClass('justify-content-center');
                    $('.selected-image .row h3').remove();
                    var arr = $.map(data, function(el) {
                        return el
                    });
                    for (var m in arr) {
                        $('.selected-image .row').append('<div class="col-sm-4">' +
                            '<div class="img gallery-img">' +
                            '<span class="remove-img"><i class="fas fa-times"></i>' +
                            '<input type="hidden" value="' + arr[m]['id'] + '">' +
                            '</span>' +
                            '<a href="' + '{{asset("storage/images/galleries")."/"}}' + arr[m][
                                'customizable_gallery'
                            ] + '" target="_blank">' +
                            '<img src="' + '{{asset("storage/images/galleries")."/"}}' + arr[m][
                                'customizable_gallery'
                            ] + '" alt="gallery image">' +
                            '</a>' +
                            '</div>' +
                            '</div>');
                    }
                }
            }
        });
        return false;
    });
</script>
@endsection
