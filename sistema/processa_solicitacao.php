<?php
include('conexao.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$data_nasc = $_POST['data_nasc'];
$id_serv = $_POST['id_serv'];
$motivo = $_POST['motivo_atend'];


$stmt = $pdo->prepare("SELECT ID_CLIENTE FROM TAB_CLIENTE WHERE CPF_CLIENTE = :cpf");
$stmt->execute(['cpf' => $cpf]);
if ($cli = $stmt->fetch()) {
    $id_cli = $cli['ID_CLIENTE'];
} else {
    $stmt = $pdo->prepare("INSERT INTO TAB_CLIENTE (NOME_CLIENTE, CPF_CLIENTE, DATA_NASCIMENTO) VALUES (:nome, :cpf, :data_nasc) RETURNING ID_CLIENTE");
    $stmt->execute(['nome' => $nome, 'cpf' => $cpf, 'data_nasc' => $data_nasc]);
    $id_cli = $stmt->fetchColumn();
}


$prefixes = [1 => 'VALID', 2 => 'CONFE', 3 => 'BIOME'];
$prefix = $prefixes[$id_serv] ?? 'ATEND';

$sql_atend = "INSERT INTO PRD_ATENDIMENTO (STATUS_ATEND, MOTIVO_ATEND, FEEDBACK, TEMPO_ATEND, ID_FK_CLIENTE)
              VALUES ('ABERTO', :motivo, 0, 0, :id_cli) RETURNING ID_ATEND";
$stmt = $pdo->prepare($sql_atend);
$stmt->execute(['motivo' => $motivo, 'id_cli' => $id_cli]);
$id_atend = $stmt->fetchColumn();

$protocolo = sprintf("%s.%03d.%03d-0", $prefix, $id_cli, $id_atend);
$stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET PROTOCOLO = :protocolo WHERE ID_ATEND = :id_atend");
$stmt->execute(['protocolo' => $protocolo, 'id_atend' => $id_atend]);

$stmt = $pdo->prepare("INSERT INTO AUX_SERV_ATEND (ID_FK_SERV, ID_FK_ATEND) VALUES (:id_serv, :id_atend)");
$stmt->execute(['id_serv' => $id_serv, 'id_atend' => $id_atend]);

header("Location: lista_atendimentos.php");
?>