@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    @if (!have_posts())
        <div class="alert alert-warning">
            {{ __('Sorry, no results were found.', 'sage') }}
        </div>
        <div class="search-form-wrapper">
            {!! get_search_form(false) !!}
        </div>
    @endif

    @while(have_posts())
        @php the_post() @endphp
        @include('partials.content-search')
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
