<?php
include('conexao.php');

$id_atend = $_GET['id'] ?? ($_POST['id_atend'] ?? 0);
$id_serv = $_GET['serv'] ?? ($_POST['id_serv'] ?? 1);
$step = $_POST['step'] ?? 1;
$score_parcial = $_POST['score_parcial'] ?? 0;

$cliente_respostas = [
    "Fiz o que pediu, mas o erro persiste.",
    "Certo, consegui avançar, e agora?",
    "A tela ficou carregando e não saiu disso.",
    "Ainda não recebi nada no meu e-mail.",
    "Pronto, deu certo. O que faço em seguida?"
];

$funcionario_respostas = [
    "Por favor, aguarde enquanto atualizo o sistema.",
    "Vou enviar um link alternativo para acesso direto.",
    "Limpe o cache e tente acessar novamente agora.",
    "Vou registrar o caso para a equipe técnica avançada.",
    "Procedimento concluído com sucesso. Atendimento finalizado."
];

if ($step == 1) {
    $sql_motivo = "SELECT ID_ITENS_ATEND, MOTIVO_PADRAO, RESPOSTA_PADRAO FROM TAB_ITENS_ATEND WHERE ID_FK_SERV = $id_serv ORDER BY RAND() LIMIT 1";
    $res_motivo = mysqli_query($c, $sql_motivo);
    $simulacao = mysqli_fetch_assoc($res_motivo);

    $sql_respostas = "SELECT RESPOSTA_PADRAO FROM TAB_ITENS_ATEND WHERE ID_FK_SERV = $id_serv ORDER BY RAND() LIMIT 10";
    $res_respostas = mysqli_query($c, $sql_respostas);
    $respostas_opcoes = [];
    while($row = mysqli_fetch_assoc($res_respostas)) {
        $respostas_opcoes[] = $row['RESPOSTA_PADRAO'];
    }
    if (!in_array($simulacao['RESPOSTA_PADRAO'], $respostas_opcoes)) {
        $respostas_opcoes[0] = $simulacao['RESPOSTA_PADRAO']; 
    }
    shuffle($respostas_opcoes);
    
    $msg_cliente = $simulacao['MOTIVO_PADRAO'];
    $resposta_correta = $simulacao['RESPOSTA_PADRAO'];
    $id_item = $simulacao['ID_ITENS_ATEND'];

} elseif ($step == 2) {
    $resposta_selecionada = $_POST['resposta_atendente'];
    $resposta_correta = $_POST['resposta_correta'];
    $id_item = $_POST['id_item_atend'];
    
    $score_parcial = ($resposta_selecionada === $resposta_correta) ? 3 : 0;
    
    $msg_cliente = $cliente_respostas[array_rand($cliente_respostas)];
    $respostas_opcoes = $funcionario_respostas;
    shuffle($respostas_opcoes);

// chat.php (Alteração no Passo 3 para gerar feedback randômico de 1 a 5)

} elseif ($step == 3) {
    $id_item = $_POST['id_item_atend'];
    $feedback_cliente = rand(1, 5); // Gera nota aleatória do cliente
    
    mysqli_query($c, "UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'CONCLUÍDO', FEEDBACK = $feedback_cliente, TEMPO_ATEND = 10 WHERE ID_ATEND = $id_atend");
    mysqli_query($c, "INSERT INTO AUX_ITENS_ATEND (ID_FK_ATEND, ID_FK_ITENS) VALUES ($id_atend, $id_item)");
    
    header("Location: lista_atendimentos.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chat de Atendimento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>Simulação de Atendimento (Interação <?= $step ?> de 2)</h2>
        
        <div class="card" style="background: #f1f5f9; border-left: 4px solid #3b82f6;">
            <p><small>Cliente diz:</small></p>
            <p><strong>"<?= $msg_cliente ?>"</strong></p>
        </div>

        <form method="POST">
            <input type="hidden" name="id_atend" value="<?= $id_atend ?>">
            <input type="hidden" name="id_serv" value="<?= $id_serv ?>">
            <input type="hidden" name="step" value="<?= $step + 1 ?>">
            <input type="hidden" name="id_item_atend" value="<?= $id_item ?>">
            <input type="hidden" name="score_parcial" value="<?= $score_parcial ?>">
            <?php if($step == 1): ?>
                <input type="hidden" name="resposta_correta" value="<?= $resposta_correta ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Sua Resposta:</label>
                <select name="resposta_atendente" required>
                    <option value="">Selecione...</option>
                    <?php foreach($respostas_opcoes as $resp): ?>
                        <option value="<?= htmlspecialchars($resp, ENT_QUOTES) ?>"><?= htmlspecialchars($resp, ENT_QUOTES) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" style="width: 100%;"><?= $step == 2 ? 'Finalizar Atendimento' : 'Enviar Resposta' ?></button>
        </form>
    </div>
</body>
</html>