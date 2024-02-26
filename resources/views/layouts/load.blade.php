@yield('styles')

@yield('content')
<script src="{{asset('assets/admin/js/vendors/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vendors/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{asset('assets/admin/js/plugin.js')}}"></script>
<script src="{{asset('assets/admin/js/tag-it.js')}}"></script>
<!-- Load trumbo -->
<script src="{{asset('assets/admin/js/trumbowyg/plugins/resizimg/resizable-resolveconflict.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery-resizable.js')}}"></script>
<script src="{{asset('assets/admin/js/trumbowyg/trumbowyg.min.js')}}">
    var trumbo_lang = "en";
</script>
@if (file_exists(public_path()."/assets/admin/js/trumbowyg/langs/".$current_locale.".min.js"))
<script>
    trumbo_lang = "{{str_replace('-','_',$current_locale)}}";
</script>
<script src="{{asset('assets/admin/js/trumbowyg/langs/'.$current_locale.'.min.js')}}"></script>
@endif
<script src="{{asset('assets/admin/js/trumbowyg/trumbowyg-all-plugins.min.js')}}"></script>
<!-- -->
<script src="{{asset('assets/admin/js/load.js')}}"></script>


@yield('scripts')