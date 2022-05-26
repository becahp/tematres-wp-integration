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
    $myArray['0'] = "Está funcionando!";

    return $myArray;
}

function tematres_wp_busca($data) {
    $termo = $data['q'];

    //$termo = $_POST['termo'];
    if (empty($termo)) {
        echo "Termo vazio";
    }
    else {
        $urlTematres = get_option('pagina_config_tematres_url');
        //echo "<p>Query de busca é \"$termo\"</p>";
        $urlBusca = $urlTematres . "?task=search&arg=" . strtolower($termo);
        //echo "<p>URL de busca é: $urlBusca</p>";
        $xml = simplexml_load_file($urlBusca)->result;

        if (empty($xml)) {
            return array();
        }

        //var_dump($xml);
        //carrega o arquivo XML e retornando um Array
        $termos = array();

        foreach ($xml->term as $item) {
            array_push($termos, (string)($item->string));
        }

        //var_dump($termos);
        $data = array();
        for ($i = 0;$i < count($termos);++$i) {
            //https://makitweb.com/loading-data-remotely-in-select2-with-ajax/
            $data[] = array(
                "id" => $termos[$i],
                "text" => $termos[$i]
            );
        }

        return $data;
        //return json_encode($data);
        //return $termos;
        //wp_send_json_success( $termos );
        //https://localhost/wp-json/tematres-wp-integration/v1/termo/teste
    }
}