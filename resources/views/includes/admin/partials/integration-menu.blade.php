<ul class="nav flex-column">
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations') }}">{{__('Tawk.to')}}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/jivochat') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations/jivochat') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations-jivochat') }}">{{__('Jivochat')}}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/disqus') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations/disqus') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations-disqus') }}">{{__('Disqus')}}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/social/facebook') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/social/facebook') ? 'text-success' : ''}}"
        href="{{ route('admin-social-facebook') }}">{{ __('Facebook Login') }}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/social/google') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/social/google') ? 'text-success' : ''}}"
        href="{{ route('admin-social-google') }}">{{ __('Google Login') }}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/seotools/analytics') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/seotools/analytics') ? 'text-success' : ''}}"
        href="{{ route('admin-seotool-analytics') }}">{{ __('Google Analytics') }}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/seotools/fbpixel') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/seotools/fbpixel') ? 'text-success' : ''}}"
        href="{{ route('admin-seotool-fbpixel') }}">{{ __('Facebook Pixel') }}</a>
    </li>
    @if(config('features.marketplace'))
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/cronjob') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations/cronjob') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations-cronjob') }}">{{__('Cronjob')}}</a>
    </li>
    @endif
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/ftp') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations/ftp') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations-ftp') }}">{{__('FTP')}}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/xml') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/general-settings/integrations/xml') ? 'text-success' : ''}}"
        href="{{ route('admin-gs-integrations-xml') }}">{{__('XML')}}</a>
    </li>
    <li
    class="nav-item border-bottom mb-2 {{request()->is('admin/seotools/tagmanager') ? 'border-success' : ''}}">
    <a class="nav-link {{request()->is('admin/seotools/tagmanager') ? 'text-success' : ''}}"
        href="{{ route('admin-seotool-tagmanager') }}">{{ __('Tag Manager') }}</a>
    </li>
    @if(config('mercadolivre.is_active'))
    <li
        class="nav-item border-bottom mb-2 {{request()->is('admin/general-settings/integrations/mercadolivre') ? 'border-success' : ''}}">
        <a class="nav-link {{request()->is('admin/general-settings/integrations/mercadolivre') ? 'text-success' : ''}}"
            href="{{ route('admin-gs-integrations-mercadolivre-index') }}">{{ __('Mercado Livre') }}</a>
    </li>
    @endif
    @if (config('services.bling.id') && config('services.bling.secret'))    
        <li class="nav-item border-bottom mb-2 {{request()->is('admin/authenticate/*') ? 'border-success' : ''}}">
            <a class="nav-link {{request()->is('admin/authenticate/*') ? 'text-success' : ''}}"
                href="{{ route('admin.bling.authenticate') }}">{{ __('Bling') }}</a>
        </li>
    @endif
</ul>