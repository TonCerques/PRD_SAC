<?php
include('conexao.php');

$pdo->query("UPDATE PRD_ATENDIMENTO SET STATUS_ATEND = 'EXPIRADO' WHERE STATUS_ATEND = 'ABERTO' AND VALIDADE_ATEND < CURRENT_TIMESTAMP");

$sql = "SELECT DISTINCT ON (a.ID_ATEND)
            a.ID_ATEND, a.PROTOCOLO, a.STATUS_ATEND, a.VALIDADE_ATEND, a.MOTIVO_ATEND, a.FEEDBACK,
            c.NOME_CLIENTE, c.CPF_CLIENTE,
            s.TIPO_SERV, s.ID_SERV,
            its.VALIDACAO, its.CONFERENCIA, its.BIOMETRIA_DIGITAL
        FROM PRD_ATENDIMENTO a
        LEFT JOIN TAB_CLIENTE c ON a.ID_FK_CLIENTE = c.ID_CLIENTE
        LEFT JOIN AUX_SERV_ATEND ax ON a.ID_ATEND = ax.ID_FK_ATEND
        LEFT JOIN TAB_SERVICOS s ON ax.ID_FK_SERV = s.ID_SERV
        LEFT JOIN AUX_ITENS_ATEND aia ON a.ID_ATEND = aia.ID_FK_ATEND
        LEFT JOIN TAB_ITENS_ATEND tia ON aia.ID_FK_ITENS = tia.ID_ITENS_ATEND
        LEFT JOIN TAB_ITENS_SERV its ON tia.ID_FK_ITEM_SERV = its.ID_ITENS_SERV
        ORDER BY a.ID_ATEND DESC";

$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Atendimentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <h2>Painel de Atendimentos</h2>
        <table>
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Tipo</th>
                    <th>Validade</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = $stmt->fetch()):
                    $sub_servico = $r['VALIDACAO'] ?? $r['CONFERENCIA'] ?? $r['BIOMETRIA_DIGITAL'] ?? 'Não especificado';
                ?>
                <tr>
                    <td><strong><?= $r['PROTOCOLO'] ?></strong></td>
                    <td><?= $r['NOME_CLIENTE'] ?><br><small><?= $r['CPF_CLIENTE'] ?></small></td>
                    <td><?= $r['TIPO_SERV'] ?></td>
                    <td><?= $sub_servico ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($r['VALIDADE_ATEND'])) ?></td>
                    <td><span class="badge st-<?= str_replace(' ', '-', $r['STATUS_ATEND']) ?>"><?= mb_strtoupper($r['STATUS_ATEND']) ?></span></td>
                    
                    <!-- Exibição do Feedback em Estrelas -->
                    <td style="color: #eab308; font-size: 1.2rem;">
                        <?= strtoupper($r['STATUS_ATEND']) === 'CONCLUÍDO' ? str_repeat('★', $r['FEEDBACK']) . str_repeat('☆', 5 - $r['FEEDBACK']) : '-' ?>
                    </td>

                    <td>
                        <?php if (strtoupper($r['STATUS_ATEND']) === 'ABERTO'): ?>
                            <a href="acoes.php?acao=assumir&id=<?= $r['ID_ATEND'] ?>"><button>Assumir</button></a>
                        <?php elseif (strtoupper($r['STATUS_ATEND']) === 'EM ATENDIMENTO'): ?>
                            <a href="chat.php?id=<?= $r['ID_ATEND'] ?>&serv=<?= $r['ID_SERV'] ?>"><button style="background: #eab308;">Chat</button></a>
                        <?php elseif (strtoupper($r['STATUS_ATEND']) === 'EXPIRADO'): ?>
                            <a href="acoes.php?acao=reatendimento&id=<?= $r['ID_ATEND'] ?>"><button style="background: #ef4444;">Reatender</button></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>