<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="@asset('images/favicon.ico')" rel="shortcut icon">
    <link href="@asset('images/touch-icon.png')" rel="apple-touch-icon-precomposed">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta name="google-site-verification" content="EBS4XKei4WioxC3QXiRKkBuV8cAOzt0TgetW2twkO4w" />

    @if(get_field('blog_description', 'option'))
        <meta name="description" content="{{ get_field('blog_description', 'option') }}" />
    @endif

    @if(!is_single())
        {{--@php var_dump(get_post()) @endphp--}}
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{ bloginfo('name') }} - {{ bloginfo('description') }} " />
        <meta property="og:description" content="{{ get_field('blog_description', 'option') }}" />
        <meta property="og:url" content="{{ get_home_url() }}" />
        <meta name="twitter:title" content="{{ bloginfo('name') }} - {{ bloginfo('description') }}" />
        <meta name="twitter:description" content="{{ get_field('blog_description', 'option') }}" />
        <meta name="twitter:card" content="summary_large_image" />

        @if(get_field('blog_share_image', 'option'))
            <meta property="og:image" content="{{ get_field('blog_share_image', 'option')['url'] }}" />
            <meta property="og:image:width" content="{{ get_field('blog_share_image', 'option')['width'] }}" />
            <meta property="og:image:height" content="{{ get_field('blog_share_image', 'option')['height'] }}" />
            <meta name="twitter:image" content="{{ get_field('blog_share_image', 'option')['url'] }}" />
        @endif
    @else
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{ get_post()->post_title }}" />
        <meta property="og:description" content="{{ get_field('description', get_post()->ID) }}" />
        <meta property="og:url" content="{{ get_permalink() . '?job=' . get_post()->post_name }}" />
        <meta name="twitter:title" content="{{ get_post()->post_title }}" />
        <meta name="twitter:description" content="{{ get_field('description', get_post()->ID) }}" />
        <meta name="twitter:card" content="summary_large_image" />

        @if(has_post_thumbnail())
            @php
                $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0];
                $feature_image_width = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[1];
                $feature_image_height = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[2];
            @endphp
            <meta property="og:image" content="{{ get_home_url() }}{{ $feature_image_url }}" />
            <meta name="twitter:image" content="{{ get_home_url() }}{{ $feature_image_url }}" />
            <meta property="og:image:width" content="{{ $feature_image_width }}" />
            <meta property="og:image:height" content="{{ $feature_image_height }}" />
        @endif
    @endif

    @php wp_head() @endphp

</head>


