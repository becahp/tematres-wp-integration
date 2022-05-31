<?php
// ----------------------------------------------------------------------------------------------------------- ROTAS
add_action('rest_api_init', function () {
    register_rest_route('tematres-wp-integration/v1', 'teste', array(
        'methods' => 'GET',
        'callback' => 'tematres_wp_teste',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('tematres-wp-integration/v1', 'termo', array(
        'methods' => 'GET',
        'callback' => 'tematres_wp_busca',
    ));
});

// ----------------------------------------------------------------------------------------------------------- Funções reais
function tematres_wp_teste() {
    /*
        Função para teste de funcionamento
    */
    $myArray['0'] = __("It's alive!", 'tematres-wp-integration');

    return $myArray;
}

function tematres_wp_busca($data) {
    $termo = $data['q'];

    if (empty($termo)) {
        echo __('Empty search term', 'tematres-wp-integration');
    }
    else {
        $urlTematres = get_option('pagina_config_tematres_url');
        
        $urlBusca = $urlTematres . "?task=search&arg=" . strtolower($termo);
        
        $xml = simplexml_load_file($urlBusca)->result;

        if (empty($xml)) {
            return array();
        }

        //carrega o arquivo XML e retornando um Array
        $termos = array();

        foreach ($xml->term as $item) {
            array_push($termos, (string)($item->string));
        }

        $data = array();
        for ($i = 0;$i < count($termos);++$i) {
            $data[] = array(
                "id" => $termos[$i],
                "text" => $termos[$i]
            );
        }

        return $data;
    }
}