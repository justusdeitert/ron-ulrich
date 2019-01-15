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
        <i class="material-icons">arrow_drop_down</i>
        <span>{{ __('More Info', 'sage') }}</span>
    </div>
    <hr>

    @while (have_posts())
        @php the_post() @endphp
        @include('partials.content-'.get_post_type())
    @endwhile

    @if(paginate_links())
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
