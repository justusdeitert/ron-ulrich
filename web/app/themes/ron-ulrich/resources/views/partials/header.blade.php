<header>
    <div class="container">
        <a class="brand" href="{{ home_url('/') }}">
            @if(get_field('brand_image', 'option'))
                {{--@php var_dump(get_field('person_image', 'option')['sizes']['medium_large']) @endphp--}}
                <div class="brand-image d-none d-sm-block " style="background-image: url({{ get_field('brand_image', 'option')['sizes']['medium_large'] }})"></div>
            @endif
            <div class="brand-right">
                <h1>{{ get_bloginfo('name') }}</h1>
                <h2>{{ bloginfo('description') }}</h2>
            </div>
        </a>
        <nav class="nav-primary d-none d-sm-block">
            {{--@if (has_nav_menu('header_navigation'))--}}
                {!! wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    // 'menu' => '',
                    // 'container' => '',
                    'menu_class' => 'nav-list'
                ]) !!}
            {{--@endif--}}
        </nav>
        <span id="toggle-sidebar" class="sidebar-toggle d-block d-sm-none">
            <i class="material-icons">menu</i>
        </span>
        <div id="sidebar">
            <!--
            simpler-sidebar will handle #sidebar's position.

            To let the content of your sidebar overflow, especially when you have a lot of content in it, you have to add a "wrapper" that wraps all content.

            TIP: provide a background color.
            -->
            <div id="sidebar-wrapper" class="sidebar-wrapper">
                <!--
                Links below are just an example. Give each clickable element, for example links, a class to trigger the closing animation.
                -->
                {{--<a class="close-sidebar" href="#">Link</a>--}}
                {{--<a class="close-sidebar" href="#">Link</a>--}}
                {{--<a class="close-sidebar" href="#">Link</a>--}}
                {{--<a class="close-sidebar" href="#">Link</a>--}}
                {!! wp_nav_menu([
                 'theme_location' => 'header_navigation',
                 // 'menu' => '',
                 // 'container' => '',
                 'menu_class' => 'nav-list'
             ]) !!}
            </div>
        </div>
    </div>
</header>
