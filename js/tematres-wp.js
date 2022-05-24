jQuery( document ).ready( function ( $ ) {

  $( '.tematres-wp-integration-escolhas1' ).select2( {
    ajax: {
      url: 'https://api.github.com/search/repositories',
      dataType: 'json'
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    }
  } );

  $( '.tematres-wp-integration-escolhas0' ).select2( {

    ajax: {
      url: 'https://api.github.com/orgs/select2/repos',
      data: function ( params ) {
        var query = {
          search: params.term,
          type: 'public'
        }

        // Query parameters will be ?search=[term]&type=public
        return query;
      }
    }

    // ajax: {
    //   //   type: "POST",
    //   url: '/wp-json/tematres-wp-integration/v1/termo/teste',
    //   contentType: "application/json; charset=utf-8",
    //   dataType: 'json',
    //   data: function ( term ) {
    //     return ( JSON.stringify( { searchString: term.term } ) )
    //   }
    // }
  } );


  $( '.tematres-wp-integration-escolhas2' ).select2( {
    tags: true,
    multiple: true,
    tokenSeparators: [ ',', ' ' ],
    minimumInputLength: 2,
    minimumResultsForSearch: 10,
    ajax: {
      url: '/wp-json/tematres-wp-integration/v1/termo/teste',
      dataType: "json",
      type: "GET",
      data: function ( params ) {

        var queryParameters = {
          term: params.term
        }
        return queryParameters;
      },
      processResults: function ( data ) {
        return {
          results: $.map( data, function ( item ) {
            return {
              text: item.tag_value,
              id: item.tag_id
            }
          } )
        };
      }
    }
  } );


  $( '.tematres-wp-integration-escolhas' ).select2( {
    minimumInputLength: 3,
    ajax: {
      url: '/wp-json/tematres-wp-integration/v1/termo/',
      processResults: function ( response ) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: response
        };
      }
    }
  } );

} ); //fim do ajax