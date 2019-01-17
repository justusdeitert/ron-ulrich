<article @php post_class('article-overview') @endphp>
    <h2 class="entry-title"><a href="{{ get_permalink() }}">{{ the_title() }}</a></h2>
    {{--<div class="entry-summary">--}}
        {{--@php the_excerpt() @endphp--}}
    {{--</div>--}}
    <a href="{{ get_permalink() }}">
        @if(get_field('description'))
            <p>{!! get_field('description') !!}</p>
        @else
            {!! the_excerpt() !!}
        @endif
    </a>
</article>

<hr>
