<article @php post_class('article-overview') @endphp>
    <div class="row article-row">
        {{--@php--}}
            {{--echo '<pre>';--}}
            {{--print_r(get_the_tags());--}}
            {{--echo '</pre>';--}}
        {{--@endphp--}}

        @if(has_post_thumbnail())
            @php $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0]; @endphp

            <div class="col-12 col-sm-4">
                <a href="{{ get_permalink() }}">
                    <div class="feature-image" style="background-image: url({{ $feature_image_url }})"></div>
                </a>
            </div>
        @endif

        <div class="col @if(!has_post_thumbnail()){!! 'full-column' !!}@endif">

            <div class="post-info">
                @if(get_the_tags())
                    <div class="row">
                        <div class="col tag-column">
                            @foreach(get_the_tags() as $tag)
                                <span class="tag">{{  $tag->name }}</span>
                                {{--@php echo get_term_link( $tag->term_id ); @endphp--}}
                            @endforeach
                        </div>
                    </div>
                @endif

                <span class="post-date">{!! get_the_date('j. F Y') !!}</span>
                {{--@php var_dump(get_field('published_in')); @endphp--}}
                @if(get_field('published_in')['activate'])
                    {!! ' / ' !!}
                    {{ __('published in:', 'sage') }}
                    <a href="{!! get_field('published_in')['url'] !!}">{!! get_field('published_in')['name'] !!}</a>
                @endif
            </div>

            {{--<h2 class="entry-title"><a href="{{ get_permalink() }}">{{ the_title() }}</a></h2>--}}
            <a href="{{ get_permalink() }}">
                <h2>{{ the_title() }}</h2>
            </a>

            <a href="{{ get_permalink() }}">
                @if(get_field('description'))
                    <p>{!! get_field('description') !!}</p>
                @else
                    {!! the_excerpt() !!}
                @endif
            </a>

            <a href="{{ get_permalink() }}">
                <div class="continue-reading">
                    <i class="material-icons">arrow_right</i>
                    <span>weiterlesen</span>
                </div>
            </a>
        </div>
    </div>
    <hr>
</article>
