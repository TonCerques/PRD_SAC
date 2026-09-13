<?php
include('conexao.php');

$acao = $_GET['acao'] ?? '';
$id = $_GET['id'] ?? 0;

if ($acao === 'assumir') {
 
    mysqli_query($c, "UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'EM ATENDIMENTO', ID_FK_FUNC = 1 WHERE ID_ATEND = $id");
    header("Location: lista_atendimentos.php");

} elseif ($acao === 'reatendimento') {
  
    $aceitou = true;

    if ($aceitou) {
        mysqli_query($c, "UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'EM ATENDIMENTO', VALIDADE_ATEND = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE ID_ATEND = $id");
    } else {
        mysqli_query($c, "UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'CANCELADO' WHERE ID_ATEND = $id");
    }
    header("Location: lista_atendimentos.php");

} elseif ($acao === 'finalizar') {
    // Formulário simples/Processo de finalização e coleta de Feedback
    mysqli_query($c, "UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'CONCLUÍDO', FEEDBACK = 5, TEMPO_ATEND = 15 WHERE ID_ATEND = $id");
    header("Location: lista_atendimentos.php");
}
?>