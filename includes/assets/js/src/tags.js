/*global wcv_tag_search_params */
jQuery( function( $ ) {

  function getEnhancedSelectFormatString() {
    var formatString = {
      formatMatches: function( matches ) {
        if ( 1 === matches ) {
          return wcv_tag_search_params.i18n_matches_1;
        }

        return wcv_tag_search_params.i18n_matches_n.replace( '%qty%', matches );
      },
      formatNoMatches: function() {
        return wcv_tag_search_params.i18n_no_matches;
      },
      formatAjaxError: function( jqXHR, textStatus, errorThrown ) {
        return wcv_tag_search_params.i18n_ajax_error;
      },
      formatInputTooShort: function( input, min ) {
        var number = min - input.length;

        if ( 1 === number ) {
          return wcv_tag_search_params.i18n_input_too_short_1;
        }

        return wcv_tag_search_params.i18n_input_too_short_n.replace( '%qty%', number );
      },
      formatInputTooLong: function( input, max ) {
        var number = input.length - max;

        if ( 1 === number ) {
          return wcv_tag_search_params.i18n_input_too_long_1;
        }

        return wcv_tag_search_params.i18n_input_too_long_n.replace( '%qty%', number );
      },
      formatSelectionTooBig: function( limit ) {
        if ( 1 === limit ) {
          return wcv_tag_search_params.i18n_selection_too_long_1;
        }

        return wcv_tag_search_params.i18n_selection_too_long_n.replace( '%qty%', limit );
      },
      formatLoadMore: function( pageNumber ) {
        return wcv_tag_search_params.i18n_load_more;
      },
      formatSearching: function() {
        return wcv_tag_search_params.i18n_searching;
      }
    };

    return formatString;
  }

  $( 'body' )

    .on( 'wcv-search-tag-init', function() {

        // Ajax product tag search box
        $( ':input.wcv-tag-search' ).filter( ':not(.enhanced)' ).each( function() {
          var select2_args = {
            allowClear:  $( this ).data( 'allow_clear' ) ? true : false,
            placeholder: $( this ).data( 'placeholder' ),
            tags:        $( this ).data( 'tags' ), 
            tokenSeparators: [",", " "],
            minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '2',
            escapeMarkup: function( m ) {
              return m;
            },
            createSearchChoice: function( term, data ) {
              if ($(data).filter( function() {
                return this.text.localeCompare( term ) === 0;
              }).length === 0) {
                return {
                  id: term,
                  text: term
                };
              }
            },
            ajax: {
                  url:         wcv_tag_search_params.ajax_url,
                  dataType:    'json',
                  quietMillis: 250,
                  data: function( term, page ) {
                      return {
                  term:     term,
                  action:   $( this ).data( 'action' ) || 'wcv_json_search_tags',
                  security: wcv_tag_search_params.nonce
                      };
                  },
                  results: function( data, page ) {
                    var terms = [];
                    if ( data ) {
                  $.each( data, function( id, text ) {
                    terms.push( { id: id, text: text } );
                  });
                }
                      return { results: terms };
                  },
                  cache: true
              }
          };

          if ( $( this ).data( 'multiple' ) === true ) {
            select2_args.multiple = true;
            select2_args.initSelection = function( element, callback ) {
              var data     = $.parseJSON( element.attr( 'data-selected' ) );
              var selected = [];

              $( element.val().split( "," ) ).each( function( i, val ) {
                selected.push( { id: val, text: data[ val ] } );
              });
              return callback( selected );
            };
            select2_args.formatSelection = function( data ) {
              return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
            };
          } else {
            select2_args.multiple = false;
            select2_args.initSelection = function( element, callback ) {
              var data = {id: element.val(), text: element.attr( 'data-selected' )};
              return callback( data );
            };
          }

          select2_args = $.extend( select2_args, getEnhancedSelectFormatString() );

          $( this ).select2( select2_args ).addClass( 'enhanced' );
        });

      })

      .trigger( 'wcv-search-tag-init' );

});