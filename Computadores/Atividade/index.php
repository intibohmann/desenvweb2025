<?php
require_once("../Classes/Computadores.class.php");

if (!defined('PATH_UPLOAD')) {
    define('PATH_UPLOAD', __DIR__ . '/uploads/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $sistema_operacional = isset($_POST['sistema_operacional']) ? $_POST['sistema_operacional'] : "";
    $marca = isset($_POST['marca']) ? $_POST['marca'] : "";
    $possui_placa_video = isset($_POST['possui_placa_video']) ? 1 : 0;
    $memoria_ram_gb = isset($_POST['memoria_ram_gb']) ? $_POST['memoria_ram_gb'] : 0;
    $cpu = isset($_POST['cpu']) ? $_POST['cpu'] : "";
    $ano = isset($_POST['ano']) ? $_POST['ano'] : 0;
    $acao = isset($_POST['acao']) ? $_POST['acao'] : "";

    // Upload da imagem
    $imagem_nome = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = 'uploads/' . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], PATH_UPLOAD . basename($_FILES['imagem']['name']));
    } elseif (isset($_POST['imagem_atual'])) {
        $imagem_nome = $_POST['imagem_atual'];
    }

    $computador = new Computador($id, $sistema_operacional, $marca, $possui_placa_video, $memoria_ram_gb, $cpu, $ano, $imagem_nome);

    if ($acao == 'salvar') {
        if ($id > 0)
            $resultado = $computador->alterar();
        else
            $resultado = $computador->inserir();
    } elseif ($acao == 'excluir') {
        $resultado = $computador->excluir();
    }

    if ($resultado)
        header("Location: index.php");
    else
    $formulario = '';
    $form_path = __DIR__ . '/form_cad_computador.html';
    if (file_exists($form_path)) {
        $formulario = file_get_contents($form_path);
    } else {
        die("Erro: O arquivo form_cad_computador.html não foi encontrado.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $formulario = file_get_contents('form_cad_computador.html');

    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $resultado = Computador::listar(1, $id);
    if ($resultado) {
        $computador = $resultado[0];
        $formulario = str_replace('{id}', $computador->getId(), $formulario);
        $formulario = str_replace('{sistema_operacional}', $computador->getSistemaOperacional(), $formulario);
        $formulario = str_replace('{marca}', $computador->getMarca(), $formulario);
        $formulario = str_replace('{possui_placa_video}', $computador->getPossuiPlacaVideo() ? 'checked' : '', $formulario);
        $formulario = str_replace('{memoria_ram_gb}', $computador->getMemoriaRamGb(), $formulario);
        $formulario = str_replace('{cpu}', $computador->getCpu(), $formulario);
        $formulario = str_replace('{ano}', $computador->getAno(), $formulario);
        $formulario = str_replace('{imagem_nome}', $computador->getImagemUrl(), $formulario);
    } else {
        $formulario = str_replace('{id}', 0, $formulario);
        $formulario = str_replace('{sistema_operacional}', '', $formulario);
        $formulario = str_replace('{marca}', '', $formulario);
        $formulario = str_replace('{possui_placa_video}', '', $formulario);
        $formulario = str_replace('{memoria_ram_gb}', '', $formulario);
        $formulario = str_replace('{cpu}', '', $formulario);
        $formulario = str_replace('{ano}', '', $formulario);
        $formulario = str_replace('{imagem_nome}', '', $formulario);
    }
    print($formulario);
    include_once('lista_computador.php');
}
?>