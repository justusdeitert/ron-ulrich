<footer>
  <div class="container">
    {{--@php dynamic_sidebar('sidebar-footer') @endphp--}}
      <div class="copyright">
          @if(get_field('copyright', 'option'))
              <p>{{ get_field('copyright', 'option') }}</p>
          @endif
      </div>
      @if (has_nav_menu('footer_navigation'))
          {!! wp_nav_menu([
              'theme_location' => 'footer_navigation',
              'menu_class' => 'footer-nav',
              'container' => '' // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
          ]) !!}
      @endif
  </div>
</footer>
