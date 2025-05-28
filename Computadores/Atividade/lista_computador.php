<?php
    require_once("../Classes/Computadores.class.php");
    $busca = isset($_GET['busca']) ? $_GET['busca'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;

    $lista = Computador::listar($tipo, $busca);
    $itens = '';
    foreach($lista as $computador){
        $item = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'itens_listagem_computadores.html');
        $item = str_replace('{id}', $computador->getId(), $item);
        $item = str_replace('{sistema_operacional}', $computador->getSistemaOperacional(), $item);
        $item = str_replace('{marca}', $computador->getMarca(), $item);
        $item = str_replace('{possui_placa_video}', $computador->getPossuiPlacaVideo() ? 'Sim' : 'Não', $item);
        $item = str_replace('{memoria_ram_gb}', $computador->getMemoriaRamGb(), $item);
        $item = str_replace('{cpu}', $computador->getCpu(), $item);
        $item = str_replace('{ano}', $computador->getAno(), $item);
        $item = str_replace('{imagem_nome}', $computador->getImagemNome(), $item);
        $itens .= $item;
    }
    $listagem = file_get_contents('listagem_computadores.html');
    $listagem = str_replace('{itens}', $itens, $listagem);
    print($listagem);
?>