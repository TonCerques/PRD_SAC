<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nasc = $_POST['data_nasc'];
    $id_serv = $_POST['id_serv'];

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

    $sql_atend = "INSERT INTO PRD_ATENDIMENTO (STATUS_ATEND, VALIDADE_ATEND, MOTIVO_ATEND, FEEDBACK, TEMPO_ATEND, ID_FK_CLIENTE)
                  VALUES ('ABERTO', CURRENT_TIMESTAMP + INTERVAL '1 day', 'Solicitação Inicial Web', 0, 0, :id_cli)
                  RETURNING ID_ATEND";
    $stmt = $pdo->prepare($sql_atend);
    $stmt->execute(['id_cli' => $id_cli]);
    $id_atend = $stmt->fetchColumn();

    $protocolo = sprintf("%s.%03d.%03d-0", $prefix, $id_cli, $id_atend);
    $stmt = $pdo->prepare("UPDATE PRD_ATENDIMENTO SET PROTOCOLO = :protocolo WHERE ID_ATEND = :id_atend");
    $stmt->execute(['protocolo' => $protocolo, 'id_atend' => $id_atend]);

    $stmt = $pdo->prepare("INSERT INTO AUX_SERV_ATEND (ID_FK_SERV, ID_FK_ATEND) VALUES (:id_serv, :id_atend)");
    $stmt->execute(['id_serv' => $id_serv, 'id_atend' => $id_atend]);

    header("Location: lista_atendimentos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Atendimento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Abrir Novo Atendimento</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nome Completo:</label>
                <input type="text" name="nome" required>
            </div>
            <div class="form-group">
                <label>CPF (Apenas números):</label>
                <input type="text" name="cpf" maxlength="11" pattern="\d{11}" required>
            </div>
            <div class="form-group">
                <label>Data de Nascimento:</label>
                <input type="date" name="data_nasc" required>
            </div>
            <div class="form-group">
                <label>Selecione o Serviço:</label>
                <select name="id_serv" required>
                    <option value="">Selecione...</option>
                    <option value="1">Validação (ID 1)</option>
                    <option value="2">Conferência (ID 2)</option>
                    <option value="3">Biometria Digital (ID 3)</option>
                </select>
            </div>
            <button type="submit">Solicitar Atendimento</button>
        </form>
    </div>
</body>
</html>