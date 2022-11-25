/*
 * Plugin Name: Tematres WP Integration
 * Author: Rebeca Moura e Lucas Rodrigues
 * Text Domain: tematres-wp-integration
 */

jQuery(document).ready(function ($) {

  if (document.getElementById('escolha_tags') || (document.getElementsByClassName('tematres-wp-integration-escolhas').length > 0)) {

    $('.tematres-wp-integration-escolhas').select2({
      minimumInputLength: 3,
      width: '100%',
      language: {
        inputTooShort: function () {
          return tmwpi_my_ajax_object.texto_escreva_mais;
        },
        searching: function () {
          return tmwpi_my_ajax_object.texto_pesquisa;
        },
      },
      ajax: {
        url: tmwpi_my_ajax_object.home_url + '/wp-json/tematres-wp-integration/v1/termo/',
        processResults: function (response) {
          // Transforms the top-level key of the response object from 'items' to 'results'
          return {
            results: response
          };
        }
      }
    });


    $('.tematres-wp-integration-escolhas').on('select2:select', function (e) {
      var data = e.params.data;

      //chamar a função de salvar a tag no WP core
      $.ajax({
        type: 'POST',
        url: tmwpi_my_ajax_object.ajax_url,
        data: {
          action: 'tmwpi_ajax_criar_tags',
          tag: data.text,
        },
        success: function (response) {
          console.log('Received ' + response);
        },
        error: function (response) {
          console.log('ERROR ' + response);
        }
      });
    });

  } // fim do if se existe o select id='escolha_tags'
}); //fim do ajax