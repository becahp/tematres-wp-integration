/*
 * Plugin Name: Tematres WP
 * Author: Rebeca Moura e Lucas Rodrigues
 * Text Domain: tematres-wp-integration
 */

jQuery( document ).ready( function ( $ ) {

  if ( document.getElementById( 'escolha_tags' ) ) {

    $( '.tematres-wp-integration-escolhas' ).select2( {
      minimumInputLength: 3,
      width: '100%',
      language: {
        inputTooShort: function ( ) {
          return my_ajax_object.texto_escreva_mais;
        },
        searching: function ( ) {
          return my_ajax_object.texto_pesquisa;
        },
      },
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


    $( '.tematres-wp-integration-escolhas' ).on( 'select2:select', function ( e ) {
      var data = e.params.data;

      //chamar a função de salvar a tag no WP core
      $.ajax( {
        type: 'POST',
        url: my_ajax_object.ajax_url,
        data: {
          action: 'criar_tags',
          tag: data.text,
        },
        success: function ( response ) {
          console.log( 'Received ' + response );
        },
        error: function ( response ) {
          console.log( 'ERROR ' + response );
        }
      } );
    } );

  } // fim do if se existe o select id='escolha_tags'
} ); //fim do ajax