<?php
include('conexao.php');

$acao = $_GET['acao'] ?? '';
$id = $_GET['id'] ?? 0;

if ($acao === 'assumir') {
 
    $stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'EM ATENDIMENTO', ID_FK_FUNC = 1 WHERE ID_ATEND = :id");
    $stmt->execute(['id' => $id]);
    header("Location: lista_atendimentos.php");

} elseif ($acao === 'reatendimento') {
  
    $aceitou = true;

    if ($aceitou) {
        $stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'EM ATENDIMENTO', VALIDADE_ATEND = CURRENT_TIMESTAMP + INTERVAL '1 day' WHERE ID_ATEND = :id");
        $stmt->execute(['id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'CANCELADO' WHERE ID_ATEND = :id");
        $stmt->execute(['id' => $id]);
    }
    header("Location: lista_atendimentos.php");

} elseif ($acao === 'finalizar') {
    // Formulário simples/Processo de finalização e coleta de Feedback
    $stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'CONCLUÍDO', FEEDBACK = 5, TEMPO_ATEND = 15 WHERE ID_ATEND = :id");
    $stmt->execute(['id' => $id]);
    header("Location: lista_atendimentos.php");
}
?>