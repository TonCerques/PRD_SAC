<?php
include('conexao.php');

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$data_nasc = $_POST['data_nasc'];
$id_serv = $_POST['id_serv'];
$motivo = $_POST['motivo_atend'];


$res_cli = mysqli_query($c, "SELECT ID_CLIENTE FROM TAB_CLIENTE WHERE CPF_CLIENTE = '$cpf'");
if ($cli = mysqli_fetch_assoc($res_cli)) {
    $id_cli = $cli['ID_CLIENTE'];
} else {
    mysqli_query($c, "INSERT INTO TAB_CLIENTE (NOME_CLIENTE, CPF_CLIENTE, DATA_NASCIMENTO) VALUES ('$nome', '$cpf', '$data_nasc')");
    $id_cli = mysqli_insert_id($c);
}


$prefixes = [1 => 'VALID', 2 => 'CONFE', 3 => 'BIOME'];
$prefix = $prefixes[$id_serv] ?? 'ATEND';

$sql_atend = "INSERT INTO PRD_ATENDIMENTO (STATUS_ATEND, MOTIVO_ATEND, FEEDBACK, TEMPO_ATEND, ID_FK_CLIENTE) 
              VALUES ('ABERTO', '$motivo', 0, 0, $id_cli)";
mysqli_query($c, $sql_atend);
$id_atend = mysqli_insert_id($c);

$protocolo = sprintf("%s.%03d.%03d-0", $prefix, $id_cli, $id_atend);
mysqli_query($c, $c_sql = "UPDATE PRD_ATENDIMENTO SET PROTOCOLO = '$protocolo' WHERE ID_ATEND = $id_atend");


mysqli_query($c, "INSERT INTO AUX_SERV_ATEND (ID_FK_SERV, ID_FK_ATEND) VALUES ($id_serv, $id_atend)");

header("Location: lista_atendimentos.php");
?>