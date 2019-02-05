<?php

// Do Not Copy HTML contents to wysywig on paste
// TODO: Find a better Solution for Not Copying Acf Styles
// This is Kind of nasty
// ------------------------------------>
add_filter('tiny_mce_before_init', function($in) {
    $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = 'p,span,b,strong,i,em,h3,h4,h5,h6,ul,li,ol';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
    }
    // Strip all class and id attributes
    stripped.find('*').removeAttr('id').removeAttr('class');
    // Return the clean HTML
    args.content = stripped.html();
  }";
    return $in;
});

// ----------------------------------->
// Set TinyMCE editor Formats.
// ----------------------------------->
add_filter('tiny_mce_before_init', function($init) {
    // Add block format elements you want to show in dropdown
    $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;';
    return $init;
});
