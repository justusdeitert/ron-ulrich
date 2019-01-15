<header>
    <div class="container">
        <a class="brand" href="{{ home_url('/') }}">
            <h1>{{ get_bloginfo('name') }}</h1>
            <h2>{{ bloginfo('description') }}</h2>
        </a>
        <nav class="nav-primary">
            {{--@if (has_nav_menu('header_navigation'))--}}
                {!! wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    // 'menu' => '',
                    // 'container' => '',
                    'menu_class' => 'nav-list'
                ]) !!}
            {{--@endif--}}
        </nav>
    </div>
</header>
