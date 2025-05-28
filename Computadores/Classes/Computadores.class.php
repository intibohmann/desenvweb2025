<?php
require_once ("Database.class.php");
class Computador {
    private $id;
    private $sistema_operacional;
    private $marca;
    private $possui_placa_video;
    private $memoria_ram_gb;
    private $cpu;
    private $ano;
    private $imagem_nome; // agora armazena o nome do arquivo

    public function __construct($id, $sistema_operacional, $marca, $possui_placa_video, $memoria_ram_gb, $cpu, $ano, $imagem_nome) {
        $this->id = $id;
        $this->sistema_operacional = $sistema_operacional;
        $this->marca = $marca;
        $this->possui_placa_video = $possui_placa_video;
        $this->memoria_ram_gb = $memoria_ram_gb;
        $this->cpu = $cpu;
        $this->ano = $ano;
        $this->imagem_nome = $imagem_nome;
    }

    // Métodos set
    public function setImagemNome($imagem_nome) {
        $this->imagem_nome = $imagem_nome;
    }

    // Métodos get
    public function getId() {
        return $this->id;
    }

    public function getSistemaOperacional() {
        return $this->sistema_operacional;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getPossuiPlacaVideo() {
        return $this->possui_placa_video;
    }

    public function getMemoriaRamGb() {
        return $this->memoria_ram_gb;
    }

    public function getCpu() {
        return $this->cpu;
    }

    public function getAno() {
        return $this->ano;
    }

    public function getImagemNome(): string {
        return $this->imagem_nome;
    }

    // __toString atualizado
    public function __toString(): string {
        $placaVideo = $this->possui_placa_video ? 'Sim' : 'Não';
        $imagem = $this->imagem_nome ? "\n- Imagem: uploads/" . $this->imagem_nome : '';
        return "Computador: $this->id - $this->marca
                - SO: $this->sistema_operacional
                - Placa de Vídeo: $placaVideo
                - RAM: $this->memoria_ram_gb GB
                - CPU: $this->cpu
                - Ano: $this->ano$imagem";
    }

    // Insere um computador no banco
    public function inserir(): bool {
        $sql = "INSERT INTO computador 
                    (sistema_operacional, marca, possui_placa_video, memoria_ram_gb, cpu, ano, imagem_nome)
                VALUES (:sistema_operacional, :marca, :possui_placa_video, :memoria_ram_gb, :cpu, :ano, :imagem_nome)";
        $parametros = array(
            ':sistema_operacional' => $this->getSistemaOperacional(),
            ':marca' => $this->getMarca(),
            ':possui_placa_video' => $this->getPossuiPlacaVideo(),
            ':memoria_ram_gb' => $this->getMemoriaRamGb(),
            ':cpu' => $this->getCpu(),
            ':ano' => $this->getAno(),
            ':imagem_nome' => $this->getImagemNome()
        );
        return Database::executar($sql, $parametros) == true;
    }

    // Lista computadores
    public static function listar($tipo = 0, $info = ''): array {
        $sql = "SELECT * FROM computador";
        switch ($tipo) {
            case 0: break;
            case 1: $sql .= " WHERE id = :info ORDER BY id"; break;
            case 2: $sql .= " WHERE marca LIKE :info ORDER BY marca"; $info = '%'.$info.'%'; break;
        }
        $parametros = array();
        if ($tipo > 0)
            $parametros = [':info' => $info];

        $comando = Database::executar($sql, $parametros);
        $computadores = [];
        while ($registro = $comando->fetch()) {
            $computador = new Computador(
                $registro['id'],
                $registro['sistema_operacional'],
                $registro['marca'],
                $registro['possui_placa_video'],
                $registro['memoria_ram_gb'],
                $registro['cpu'],
                $registro['ano'],
                $registro['imagem_nome']
            );
            array_push($computadores, $computador);
        }
        return $computadores;
    }

    // Altera um computador
    public function alterar(): bool {
        $sql = "UPDATE computador
                   SET sistema_operacional = :sistema_operacional,
                       marca = :marca,
                       possui_placa_video = :possui_placa_video,
                       memoria_ram_gb = :memoria_ram_gb,
                       cpu = :cpu,
                       ano = :ano,
                       imagem_nome = :imagem_nome
                 WHERE id = :id";
        $parametros = array(
            ':id' => $this->getId(),
            ':sistema_operacional' => $this->getSistemaOperacional(),
            ':marca' => $this->getMarca(),
            ':possui_placa_video' => $this->getPossuiPlacaVideo(),
            ':memoria_ram_gb' => $this->getMemoriaRamGb(),
            ':cpu' => $this->getCpu(),
            ':ano' => $this->getAno(),
            ':imagem_nome' => $this->getImagemNome()
        );
        return Database::executar($sql, $parametros) == true;
    }

    // Exclui um computador
    public function excluir(): bool {
        $sql = "DELETE FROM computador WHERE id = :id";
        $parametros = array(':id' => $this->getId());
        return Database::executar($sql, $parametros) == true;
    }

    // Os outros métodos permanecem iguais...
}
?>
