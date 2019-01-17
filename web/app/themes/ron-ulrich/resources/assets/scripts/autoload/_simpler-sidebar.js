import 'simpler-sidebar';

$( document ).ready( function() {
    $( "#sidebar" ).simplerSidebar( {
        selectors: {
            trigger: "#toggle-sidebar",
            quitter: ".close-sidebar"
        },
        align: "left", // sidebar.align
        animation: {
            duration: 300,
            easing: "swing"
        },
    } );
} );
