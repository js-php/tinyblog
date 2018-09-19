$(function(){
    var inited = false;
    var innerAvailableTags = [];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

    function source( request, response ) {
                  // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            innerAvailableTags, extractLast( request.term ) ) );
    }

    LibAutocomplete = {
        'init' : function(availableTags) {
            if (inited) {
                return false;
            }
            innerAvailableTags = availableTags;
            inited = true;
            $( "#tags" )
              // don't navigate away from the field on tab when selecting an item
              .on( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).autocomplete( "instance" ).menu.active ) {
                  event.preventDefault();
                }
              })
              .autocomplete({
                minLength: 0,
                source: source,
                // source: '/public/test.html',
                focus: function() {
                  // prevent value inserted on focus
                  return false;
                },
                select: function( event, ui ) {
                  var terms = split( this.value );
                  // remove the current input
                  terms.pop();
                  // add the selected item
                  terms.push( ui.item.value );
                  // add placeholder to get the comma-and-space at the end
                  terms.push( "" );
                  this.value = terms.join( ", " );
                  return false;
                }
              });
            return inited;
        },
        'reinit' : function(availableTags) {
            if (!inited) {
                console.warn('You must call init method thirst');
                return false;
            }
            innerAvailableTags = availableTags;
            return true;
        }
    };
});