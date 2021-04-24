$( function() {
    $( ".search-box" ).autocomplete({
      source: 'controller.php?autosearch=true'
    });
  } );
 
