<a href="{{ get_permalink() }}">
    <article @php post_class() @endphp>
        <div class="row">
            {{--@php--}}
                {{--echo '<pre>';--}}
                {{--print_r(get_the_tags());--}}
                {{--echo '</pre>';--}}
            {{--@endphp--}}

            @if(has_post_thumbnail())
                @php $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0]; @endphp
                <div class="col-12 col-sm-4">
                    <div class="feature-image" style="background-image: url({{ $feature_image_url }})"></div>
                </div>
            @endif
            <div class="col @if(!has_post_thumbnail()){!! 'full-column' !!}@endif">
                @if(get_the_tags())
                    <div class="row">
                        <div class="col tag-column">
                            @foreach(get_the_tags() as $tag)
                                <span class="tag">{{  $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{--<h2 class="entry-title"><a href="{{ get_permalink() }}">{{ the_title() }}</a></h2>--}}
                <h2>{{ the_title() }}</h2>

                @if(get_field('description'))
                    <p>{!! get_field('description') !!}</p>
                @else
                    {!! the_excerpt() !!}
                @endif

                <div class="continue-reading">
                    <i class="material-icons">arrow_right</i>
                    <span>weiterlesen</span>
                </div>
            </div>
        </div>
    </article>
</a>
<hr>
