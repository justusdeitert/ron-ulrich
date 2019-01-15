document.addEventListener( 'wpcf7invalid', function() {
    // $('[role="alert"]').addClass('alert alert-danger');
    $('.wpcf7-response-output').addClass('alert alert-danger');
    $('.wpcf7-response-output').removeClass('wpcf7-response-output wpcf7-validation-errors')
    // $('[role="alert"]').addClass('alert alert-danger');
    // $('[role="alert"]').removeClass('wpcf7-response-output');

}, false );

document.addEventListener( 'wpcf7spam', function() {
    $('.wpcf7-response-output').addClass('alert alert-warning');
    $('.wpcf7-response-output').removeClass('wpcf7-response-output wpcf7-validation-errors')
}, false );

document.addEventListener( 'wpcf7mailfailed', function() {
    $('.wpcf7-response-output').addClass('alert alert-warning');
    $('.wpcf7-response-output').removeClass('wpcf7-response-output wpcf7-validation-errors')
}, false );

document.addEventListener( 'wpcf7mailsent', function() {
    $('.wpcf7-response-output').addClass('alert alert-success');
    $('.wpcf7-response-output').removeClass('wpcf7-response-output wpcf7-validation-errors')
}, false );
