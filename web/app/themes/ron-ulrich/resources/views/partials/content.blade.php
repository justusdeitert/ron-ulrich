<article @php post_class() @endphp>
    <div class="row">
        @if(has_post_thumbnail())
            @php $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0]; @endphp
            <div class="col col-sm-4">
                <div class="feature-image" style="background-image: url({{ $feature_image_url }})"></div>
            </div>
        @endif
        <div class="col">
            <h2 class="entry-title"><a href="{{ get_permalink() }}">{{ the_title() }}</a></h2>
            {{--@include('partials/entry-meta')--}}
            <div class="entry-summary">
                @php the_excerpt() @endphp
            </div>
        </div>
    </div>
</article>
