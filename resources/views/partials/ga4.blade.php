@php($mid = config('analytics.measurement_id'))
@if(filled($mid))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $mid }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $mid }}');
        function bindCtaClicks() {
            document.querySelectorAll('[data-cta-track]').forEach(function (el) {
                el.addEventListener('click', function () {
                    var label = el.getAttribute('data-cta-track') || 'cta';
                    gtag('event', 'cta_click', { cta_label: label, link_url: el.getAttribute('href') || '' });
                });
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindCtaClicks);
        } else {
            bindCtaClicks();
        }
    </script>
@endif
