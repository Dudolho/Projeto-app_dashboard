<?php
//class dashboard
class Dashboard {

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;
    public $clientesAtivos;
    public $clientesInativos;
    public $totalReclamacoes;
    public $totalElogios;
    public $totalSugestoes;
    public $totalDespesas;

    

    public function __get($attr) {
        return $this->$attr;
    }

    public function __set($attr, $val) {
        $this->$attr = $val;
        return $this;
    }
}

//conexão com o banco
class Conexao {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar() {
        try {

            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            $conexao->exec('set charset utf8');

            return $conexao;


        } catch(PDOException $e) {
            echo('<p>'. $e .'</p>');
        }
    }
}

//database
class Db {
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard)
    {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() {
        $query = 'select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas() {
        $query = 'select SUM(total) as total_vendas from tb_vendas where data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

    public function getClientesAtivos() {
        $query = 'select count(*) as clientes_ativos from tb_clientes where cliente_ativo = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
    }

    public function getClientesInativos() {
        $query = 'select count(*) as clientes_inativos from tb_clientes where cliente_ativo = 0';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
    }

    public function getReclamacao() {
        $query = 'select count(*) as reclamacao from tb_suporte where reclamacao = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->reclamacao;
    }

    public function getSugestao() {
        $query = 'select count(*) as sugestao from tb_suporte where sugestao = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->sugestao;
    }

    public function getElogio() {
        $query = 'select count(*) as elogio from tb_suporte where elogio = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->elogio;
    }

    public function getTotalDespesas() {
        $query = 'select SUM(total) as total_despesas from tb_despesas where data_despesa between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
    }

    
}

//logica scripts

$competencia = explode('-', $_GET['competencia']);

$ano = $competencia[0];
$mes =$competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard = new Dashboard;

$dashboard->__set('data_inicio', $ano . '-' . $mes . '-01');
$dashboard->__set('data_fim', $ano . '-' . $mes . '-' . $dias_do_mes);

$conexao = new Conexao();
$db = new Db($conexao, $dashboard);

$dashboard->__set('numeroVendas', $db->getNumeroVendas());
$dashboard->__set('totalVendas', $db->getTotalVendas());
$dashboard->__set('clientesAtivos', $db->getClientesAtivos());
$dashboard->__set('clientesInativos', $db->getClientesInativos());
$dashboard->__set('totalReclamacoes', $db->getReclamacao());
$dashboard->__set('totalElogios', $db->getElogio());
$dashboard->__set('totalSugestoes', $db->getSugestao());
$dashboard->__set('totalDespesas', $db->getTotalDespesas());
echo json_encode($dashboard);


?>