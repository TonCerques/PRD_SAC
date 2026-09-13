<?php
include('conexao.php');

$st = $_POST['s_atnd'] ?? 'ABERTO';
$mt = $_POST['m_atnd'] ?? '';
$fb = $_POST['f_atnd'] ?? 0;
$tm = $_POST['t_atnd'] ?? 0;
$cli = $_POST['id_cli'] ?? 1;
$fnc = $_POST['id_func'] ?? 1;

$sql = "INSERT INTO PRD_ATENDIMENTO (STATUS_ATEND, MOTIVO_ATEND, FEEDBACK, TEMPO_ATEND, ID_FK_CLIENTE, ID_FK_FUNC) 
        VALUES ('$st', '$mt', $fb, $tm, $cli, $fnc)";

mysqli_query($c, $sql);
header("Location: index.html");
?>