<hr>

<div class="share-container">
    <span>{{ __('Share on:', 'sage') }}</span>
    {{--<i class="material-icons">arrow_drop_down</i>--}}
    @if(have_rows('share_icons', 'option'))
        {{--<h1>tes</h1>--}}
        <ul>
            @while(have_rows('share_icons', 'option'))
                @php the_row() @endphp
                @if(get_sub_field('show'))
                    <li>
                        <a class="share-clicker" href="@php global $wp; echo get_sub_field('url') . home_url($wp->request) . '/?job=' . get_post()->post_name; @endphp" target="_blank">
                            <img src="{{ get_sub_field('icon')['url'] }}" alt="{{ get_sub_field('icon')['name'] }}" >
                        </a>
                    </li>
                @endif
            @endwhile
        </ul>
    @endif

    @if(get_field('published_in')['activate'])
        <a class="go-to-article" href="{{ get_field('published_in')['url'] }}">{{ __('Go to Article', 'sage') }}</a>
    @endif
</div>

<hr>

<article @php post_class('article-detail') @endphp>
    <header>
        @if(get_the_tags())
            <div class="row">
                <div class="col tag-column">
                    @foreach(get_the_tags() as $tag)
                        <span class="tag">{{  $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="post-info">
            <span class="post-date">{!! get_the_date('j. F Y') !!}</span>
            {{--@php var_dump(get_field('published_in')); @endphp--}}
            @if(get_field('published_in')['activate'])
                {!! ' / ' !!}
                {{ __('published in:', 'sage') }}
                <a href="{!! get_field('published_in')['url'] !!}">{!! get_field('published_in')['name'] !!}</a>
            @endif
        </div>

        <h2>{{ the_title() }}</h2>

        @if(get_field('description'))
            <h3>{!! get_field('description') !!}</h3>
        @endif

        {{--@include('partials/entry-meta')--}}
    </header>

    @php the_content() @endphp
    {{--<footer>--}}
        {{--{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}--}}
    {{--</footer>--}}
    {{--@php comments_template('/partials/comments.blade.php') @endphp--}}
</article>

@if(get_field('published_in')['activate'])
    <a class="go-to-article-bottom-link" href="{{ get_field('published_in')['url'] }}">
        <i class="material-icons">arrow_right</i>
        <span>{{ __('Go to Article', 'sage') }}</span>
    </a>
@endif

