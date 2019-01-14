<header>
    <div class="container">
        <a class="brand" href="{{ home_url('/') }}">
            <h1>{{ get_bloginfo('name', 'display') }}</h1>
            <h2>Journalist</h2>
        </a>
        <nav class="nav-primary">
            {{--@if (has_nav_menu('header_navigation'))--}}
                {!! wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    'menu_class' => 'nav'
                ]) !!}
            {{--@endif--}}
        </nav>
    </div>
</header>
