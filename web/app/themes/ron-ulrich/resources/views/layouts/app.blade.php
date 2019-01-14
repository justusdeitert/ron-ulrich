<!doctype html>
<html {!! get_language_attributes() !!} class="@if(is_user_logged_in()){{ 'logged-in' }}@endif">
    @include('partials.head')
    <body @php body_class() @endphp>
        @php do_action('get_header') @endphp
        @include('partials.header')
        <main class="container" role="document">
            <div class="content">
                @yield('content')
                {{--@if (App\display_sidebar())--}}
                    {{--<aside class="sidebar">--}}
                        {{--@include('partials.sidebar')--}}
                    {{--</aside>--}}
                {{--@endif--}}
            </div>
        </main>
        @php do_action('get_footer') @endphp
        @include('partials.footer')
        @php wp_footer() @endphp
    </body>
</html>
