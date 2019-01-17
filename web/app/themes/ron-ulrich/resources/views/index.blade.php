@extends('layouts.app')

@section('content')
    {{--@include('partials.page-header')--}}

    @if (!have_posts())
        <div class="alert alert-warning">
            {{ __('Sorry, no results were found.', 'sage') }}
        </div>
        {!! get_search_form(false) !!}
    @endif

    {{--@php var_dump(get_the_tags()) @endphp--}}
    <hr>
    <div class="more-info">
        <div class="info-left">
            <i class="more material-icons">arrow_drop_down</i>
            <i class="less material-icons">arrow_drop_up</i>
            <span class="more">{{ __('More Info', 'sage') }}</span>
            <span class="less">{{ __('Less Info', 'sage') }}</span>
        </div>
        <div class="info-right">
            <div class="input-group">
                <select name="archive-dropdown" class="custom-select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                    <option value=""><?php echo esc_attr( __( 'Month' ) ); ?></option>
                    @php
                        wp_get_archives(array(
                            'type' => 'monthly',
                            'format' => 'option',
                            'show_post_count' => 1
                        ));
                    @endphp
                </select>
            </div>
            <div class="input-group">
                <select name="archive-dropdown" class="custom-select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                    <option value="">{{ esc_attr(__( 'Tags' )) }}</option>
                    @foreach(get_tags() as $tag)
                        @php $tag_link = get_tag_link( $tag->term_id ); @endphp
                        <option value="{{ $tag_link }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <hr>

    @while (have_posts())
        @php the_post() @endphp
        @include('partials.content-'.get_post_type())
    @endwhile

    @if(paginate_links())
        <hr>
        <div class="pagination">
            {!!
                paginate_links(array(
                    'prev_text' => '<i class="material-icons">arrow_left</i>',
                    'next_text' => '<i class="material-icons">arrow_right</i>'
                ));
            !!}
        </div>
    @endif

@endsection
