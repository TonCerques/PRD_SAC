CREATE TABLE TAB_CLIENTE(
    ID_CLIENTE SERIAL PRIMARY KEY NOT NULL,
    NOME_CLIENTE VARCHAR(50),
    CPF_CLIENTE VARCHAR(11) NOT NULL UNIQUE CHECK(LENGTH(CPF_CLIENTE) = 11),
    DATA_NASCIMENTO DATE NOT NULL
);

CREATE TABLE TAB_SERVICOS(
    ID_SERV INTEGER PRIMARY KEY,
    TIPO_SERV VARCHAR(50),
    PRECO_SERVICO NUMERIC(10,2)
);

CREATE TABLE TAB_FUNCIONARIO(
    ID_FUNC SERIAL PRIMARY  KEY NOT NULL,
    NOME_FUNC VARCHAR(50) NOT NULL,
    MATRICULA VARCHAR(7) NOT NULL UNIQUE  CHECK(LENGTH(MATRICULA) = 7),
    DATA_NASCIMENTO DATE NOT NULL 
);


CREATE TABLE PRD_ATENDIMENTO(
    ID_ATEND SERIAL PRIMARY KEY NOT NULL,
    STATUS_ATEND VARCHAR(50) DEFAULT 'ABERTO',
    VALIDADE_ATEND TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MOTIVO_ATEND TEXT,
    FEEDBACK INT NOT NULL CHECK(FEEDBACK >=0),
    PROTOCOLO VARCHAR(15) DEFAULT 'XXXXX.000.000-0', -- O TEMPLATE SERÁ UM SUFIXO DO TIPO DE SERVIÇO NO PROTOCOLO
    TEMPO_ATEND INT NOT NULL CHECK (TEMPO_ATEND >= 0),
    ID_FK_CLIENTE BIGINT,
    ID_FK_FUNC BIGINT,
    CONSTRAINT FK_CLIENTE FOREIGN KEY(ID_FK_CLIENTE) REFERENCES TAB_CLIENTE(ID_CLIENTE),
    CONSTRAINT FK_FUNC FOREIGN KEY(ID_FK_FUNC) REFERENCES TAB_FUNCIONARIO(ID_FUNC)
  );

CREATE TABLE TAB_ITENS_SERV(
    ID_ITENS_SERV SERIAL PRIMARY KEY,
    VALIDACAO VARCHAR(50),
    CONFERENCIA VARCHAR(50),
    BIOMETRIA_DIGITAL VARCHAR(50), 
    ID_FK_SERVICO INT NOT NULL,
    CONSTRAINT ID_FK_SERVICE FOREIGN KEY (ID_FK_SERVICO) REFERENCES TAB_SERVICOS(ID_SERV)
);


CREATE TABLE TAB_ITENS_ATEND(
    ID_ITENS_ATEND SERIAL PRIMARY KEY,
    MOTIVO_PADRAO VARCHAR(250),
    RESPOSTA_PADRAO VARCHAR(250),
    ID_FK_SERV INT NOT NULL,
    CONSTRAINT FK_SERV FOREIGN KEY (ID_FK_SERV) REFERENCES TAB_SERVICOS(ID_SERV),
    ID_FK_ITEM_SERV BIGINT  NOT NULL,
    CONSTRAINT AUX_ITEM_SERV_FK FOREIGN KEY(ID_FK_ITEM_SERV) REFERENCES TAB_ITENS_SERV(ID_ITENS_SERV)
);

CREATE TABLE AUX_ITENS_ATEND(
    ID_FK_ATEND BIGINT NOT NULL,
    CONSTRAINT AUX_FK_ATEND FOREIGN KEY(ID_FK_ATEND) REFERENCES PRD_ATENDIMENTO(ID_ATEND),
    ID_FK_ITENS BIGINT NOT NULL,
    CONSTRAINT FK_AUX_ITENS FOREIGN KEY(ID_FK_ITENS) REFERENCES TAB_ITENS_ATEND(ID_ITENS_ATEND)
);


CREATE TABLE AUX_SERV_ATEND(
    ID_FK_SERV INT NOT NULL,
    CONSTRAINT FK_AUX_SERV FOREIGN KEY(ID_FK_SERV) REFERENCES TAB_SERVICOS(ID_SERV),
    ID_FK_ATEND BIGINT NOT NULL,
    CONSTRAINT FK_AUX_ATEND FOREIGN KEY(ID_FK_ATEND) REFERENCES PRD_ATENDIMENTO(ID_ATEND)
);


INSERT INTO PRD_SAC.TAB_CLIENTE(NOME_CLIENTE, CPF_CLIENTE, DATA_NASCIMENTO)
VALUES
('TASHA SANTOS', '53920184726', '2004-06-12'),
('GUSTAVO AMORIM', '10485937261', '1995-09-18'),
('GABY LOURENÇO', '84729103658', '1994-08-10'),
('MICHEL TEOBALDO', '29581047213', '2004-06-03'),
('WELLINGTON CERQUEIRA', '71048293654', '1997-03-21'),
('JOSENEIDE MARQUES', '48295107386', '1986-12-31'),
('ZELIA NILDES', '93018472516', '2002-06-15'),
('HUGO RUFINO', '07456931842', '1994-05-14'),
('RAFAEL SANTOS', '33919576420', '2004-06-12');

INSERT INTO PRD_SAC.TAB_SERVICOS(ID_SERV, TIPO_SERV, PRECO_SERVICO)
VALUES
(1,'VALIDAÇÃO', 0),
(2, 'CONFERENCIA', 170.0),
(3,'BIOMETRIA_DIGITAL', 260.0);


INSERT INTO PRD_SAC.TAB_ITENS_SERV(VALIDACAO, CONFERENCIA, BIOMETRIA_DIGITAL, ID_FK_SERVICO)
VALUES
    ('Validação de agendamento de conferência', NULL, NULL, 1),
    ('Validação de atendimento', NULL, NULL, 1),
    ('Validação de acesso à conferência', NULL, NULL, 1),
    ('Validação de biometria', NULL, NULL, 1);

INSERT INTO PRD_SAC.TAB_ITENS_SERV(VALIDACAO, CONFERENCIA, BIOMETRIA_DIGITAL, ID_FK_SERVICO)
VALUES
    (NULL, 'Conferência E-CPF', NULL, 2),
    (NULL, 'Conferência E-CNPJ', NULL, 2),
    (NULL, 'Videoconferência de E-CPF', NULL, 2),
    (NULL, 'Videoconferência de E-CNPJ', NULL, 2),
    (NULL, 'Videoconferência de Retentativa E-CPF', NULL, 2),
    (NULL, 'Videoconferência de Retentativa E-CNPJ', NULL, 2);

INSERT INTO PRD_SAC.TAB_ITENS_SERV(VALIDACAO, CONFERENCIA, BIOMETRIA_DIGITAL, ID_FK_SERVICO)
VALUES
    (NULL, NULL, 'E-CPF', 3),
    (NULL, NULL, 'E-CNPJ', 3),
    (NULL, NULL, 'Biometria E-CPF', 3),
    (NULL, NULL, 'Biometria E-CNPJ', 3),
    (NULL, NULL, 'Biometria de Retentativa E-CPF', 3),
    (NULL, NULL, 'Biometria de Retentativa E-CNPJ', 3);
  

INSERT INTO PRD_SAC.TAB_FUNCIONARIO(NOME_FUNC, MATRICULA, DATA_NASCIMENTO)
VALUES 
    ('Carlos Eduardo Souza', 'B202601', '1988-04-15'),
    ('Mariana Costa Lima', 'B202602', '1993-09-22'),
    ('Ricardo Alves Pereira', 'B202603', '1985-11-05'),
    ('Ana Beatriz Ribeiro', 'B202604', '1995-02-18'),
    ('Fernando Jorge Silva', 'B202605', '1990-07-30'),
    ('Camila Oliveira Melo', 'B202606', '1997-12-12'),
    ('Lucas Gabriel Santos', 'B202607', '1983-05-25'),
    ('Juliana Martins Rocha', 'B202608', '1992-10-08'),
    ('Rodrigo Augusto Lima', 'B202609', '1989-03-14'),
    ('Patrícia Nunes Costa', 'B202610', '1996-08-27');


INSERT INTO PRD_SAC.PRD_ATENDIMENTO(
    STATUS_ATEND,
    VALIDADE_ATEND,
    MOTIVO_ATEND,
    FEEDBACK,
    PROTOCOLO,
    TEMPO_ATEND,
    ID_FK_CLIENTE,
    ID_FK_FUNC
)
VALUES 
    ('Concluído', '2026-09-14 14:00:00', 'Validação do código de acesso', 5, 'VALID.000.001-0', 12, 1, 5),
    ('ABERTO', '2026-09-15 10:30:00', 'Solicitação de Biometria', 0, 'BIOME.000.002-0', 25, 2, 2),
    ('Cancelado', '2026-09-07 11:15:00', 'Realização da videoconferência', 1, 'CONFE.000.003-0', 8, 3, 9),
    ('Concluído', '2026-09-14 09:00:00', 'Alteração cadastral', 4, 'VALID.000.004-0', 5, 4, 4),
    ('ABERTO', '2026-09-20 16:45:00', 'Realização da videoconferência', 2, 'CONFE.000.005-0', 15, 5, 1),
    ('Concluído', '2026-09-14 15:20:00', 'Realização da videoconferência', 5, 'CONFE.000.006-0', 4, 6, 7),
    ('ABERTO', '2026-09-16 08:00:00', 'Reclamação de serviço da conferência', 3, 'VALID.000.007-0', 30, 7, 3);

INSERT INTO AUX_SERV_ATEND(ID_FK_SERV, ID_FK_ATEND)
VALUES
(1, 1),
(3, 2),
(2, 3),
(1, 4),
(2, 5),
(2, 6),
(1, 7);


INSERT INTO PRD_SAC.TAB_ITENS_ATEND(MOTIVO_PADRAO,RESPOSTA_PADRAO,ID_FK_SERV,ID_FK_ITEM_SERV)
VALUES
    ('Perdi meu código de acesso', 'Refaça a conferência para recuperar o acesso.', 1, 3),
    ('Não recebi o código de acesso', 'Solicite um novo código de acesso ao atendimento.', 1, 3),
    ('Não consigo validar meu atendimento', 'Confira os dados informados e tente realizar a validação novamente.', 1, 2),
    ('Não consigo realizar a conferência', 'Verifique seus documentos e tente iniciar a conferência novamente.', 2, 5),
    ('A conferência foi recusada', 'Revise os dados enviados e realize uma nova conferência.', 2, 6),
    ('A videoconferência foi interrompida', 'Aguarde o contato da equipe para realizar uma nova tentativa.', 2, 7),
    ('A biometria não foi reconhecida', 'Limpe a câmera e repita o procedimento de biometria.', 3, 11),
    ('Preciso repetir a biometria', 'Siga as instruções na tela e faça uma nova tentativa de biometria.', 3, 15);


INSERT INTO PRD_SAC.TAB_ITENS_ATEND (MOTIVO_PADRAO, RESPOSTA_PADRAO, ID_FK_SERV, ID_FK_ITEM_SERV) VALUES

('Agendamento não encontrado no sistema', 'Verifique o número do agendamento e tente novamente.', 1, 1),
('Data do agendamento expirada', 'Realize um novo agendamento no portal.', 1, 1),
('Horário de atendimento indisponível', 'Selecione um novo horário compatível.', 1, 1),
('Erro ao confirmar agendamento', 'Aguarde alguns minutos e atualize a página.', 1, 1),
('Dúvida sobre os documentos do agendamento', 'Acesse a lista de documentos no site oficial.', 1, 1),
('Sistema de agendamento fora do ar', 'Nossa equipe técnica já está atuando. Tente mais tarde.', 1, 1),
('Quero cancelar o agendamento', 'Acesse o menu Meus Agendamentos para cancelar.', 1, 1),
('Como reagendar minha validação?', 'Cancele o atual e inicie um novo processo.', 1, 1),
('Não recebi o e-mail de confirmação', 'Verifique sua caixa de spam ou lixo eletrônico.', 1, 1),
('O link de agendamento está quebrado', 'Limpe o cache do navegador e tente acessar novamente.', 1, 1),
('Atendimento recusado pelo sistema', 'Certifique-se de que seus dados estão corretos.', 1, 2),
('Não consigo iniciar o atendimento', 'Verifique sua conexão com a internet.', 1, 2),
('Atendimento travado na tela de carregamento', 'Atualize a página pressionando F5.', 1, 2),
('Qual o prazo para o atendimento?', 'O prazo padrão é de 24 a 48 horas úteis.', 1, 2),
('Meu protocolo de atendimento sumiu', 'Consulte seu e-mail cadastrado para recuperar o protocolo.', 1, 2),
('Acesso bloqueado por tentativas', 'Aguarde 30 minutos para uma nova tentativa.', 1, 3),
('Senha de acesso inválida', 'Utilize a opção Esqueci minha senha.', 1, 3),
('Código de acesso expirado', 'Gere um novo código de acesso na plataforma.', 1, 3),
('Não consigo acessar a sala de conferência', 'Verifique se você possui os plugins necessários instalados.', 1, 3),
('Link de acesso expirado', 'Solicite um novo link de acesso ao suporte.', 1, 3),
('Erro na validação biométrica', 'Certifique-se de estar em um ambiente iluminado.', 1, 4),
('Biometria não compatível', 'Limpe a lente da câmera e tente novamente.', 1, 4),
('O sistema não lê minha digital', 'Posicione o dedo corretamente no sensor.', 1, 4),
('Reconhecimento facial falhou', 'Retire óculos, chapéu ou máscaras.', 1, 4),
('Erro de câmera na biometria', 'Conceda permissão de câmera no seu navegador.', 1, 4),


('E-CPF não validado', 'Envie um documento de identificação válido e atualizado.', 2, 5),
('Dados do E-CPF incorretos', 'Solicite a correção dos dados junto ao emissor.', 2, 5),
('E-CPF bloqueado', 'Entre em contato com a certificadora para desbloqueio.', 2, 5),
('E-CPF expirado', 'Inicie o processo de renovação do seu certificado.', 2, 5),
('Divergência de titularidade no E-CPF', 'O titular deve ser o mesmo do cadastro.', 2, 5),
('E-CNPJ não reconhecido', 'Valide o status do CNPJ na Receita Federal.', 2, 6),
('Sócio não autorizado no E-CNPJ', 'Anexe o contrato social atualizado.', 2, 6),
('E-CNPJ inativo', 'Regularize a situação da empresa antes de prosseguir.', 2, 6),
('Erro na emissão do E-CNPJ', 'Reinicie o processo de emissão.', 2, 6),
('Falta de procuração para E-CNPJ', 'Envie a procuração pública válida.', 2, 6),
('Vídeo travando na videoconferência E-CPF', 'Verifique a velocidade da sua internet.', 2, 7),
('Áudio mudo na videoconferência', 'Habilite o microfone nas configurações do sistema.', 2, 7),
('Agente de registro não apareceu (E-CPF)', 'Aguarde na sala ou solicite reagendamento.', 2, 7),
('A videoconferência E-CPF caiu', 'Acesse o link novamente para retornar à sala.', 2, 7),
('Não consigo compartilhar tela', 'Conceda permissão de compartilhamento ao navegador.', 2, 7),
('Vídeo travando na videoconferência E-CNPJ', 'Verifique a velocidade da sua internet.', 2, 8),
('Áudio mudo na videoconferência E-CNPJ', 'Habilite o microfone nas configurações do sistema.', 2, 8),
('Agente de registro não apareceu (E-CNPJ)', 'Aguarde na sala ou solicite reagendamento.', 2, 8),
('Representante legal ausente', 'O representante deve estar presente durante toda a chamada.', 2, 8),
('A videoconferência E-CNPJ caiu', 'Acesse o link novamente para retornar à sala.', 2, 8),
('Preciso remarcar retentativa E-CPF', 'Acesse o painel de retentativas para um novo agendamento.', 2, 9),
('Quantas retentativas E-CPF eu tenho?', 'Você possui até 3 retentativas gratuitas.', 2, 9),
('Erro na retentativa E-CPF', 'Entre em contato via chat para suporte manual.', 2, 9),
('Retentativa E-CPF bloqueada', 'O limite de tentativas foi excedido.', 2, 9),
('Prazo para retentativa E-CPF venceu', 'Inicie um novo processo de conferência.', 2, 9),
('Preciso remarcar retentativa E-CNPJ', 'Acesse o painel de retentativas para um novo agendamento.', 2, 10),
('Quantas retentativas E-CNPJ eu tenho?', 'Você possui até 3 retentativas gratuitas.', 2, 10),
('Erro na retentativa E-CNPJ', 'Entre em contato via chat para suporte manual.', 2, 10),
('Retentativa E-CNPJ bloqueada', 'O limite de tentativas foi excedido.', 2, 10),
('Prazo para retentativa E-CNPJ venceu', 'Inicie um novo processo de conferência.', 2, 10),


('Instalação do E-CPF falhou', 'Baixe a versão mais recente do emissor.', 3, 11),
('Token E-CPF não reconhecido', 'Troque a porta USB e tente novamente.', 3, 11),
('Esqueci a senha do token E-CPF', 'Utilize o PUK para redefinir a senha do token.', 3, 11),
('E-CPF revogado', 'Adquira um novo certificado digital.', 3, 11),
('Erro de leitura do Smartcard E-CPF', 'Limpe o chip do cartão e insira novamente.', 3, 11),
('Instalação do E-CNPJ falhou', 'Execute o instalador como Administrador.', 3, 12),
('Token E-CNPJ não reconhecido', 'Troque a porta USB e tente novamente.', 3, 12),
('Esqueci a senha do token E-CNPJ', 'Utilize o PUK para redefinir a senha do token.', 3, 12),
('E-CNPJ revogado', 'Adquira um novo certificado digital.', 3, 12),
('Erro de leitura do Smartcard E-CNPJ', 'Limpe o chip do cartão e insira novamente.', 3, 12),
('Biometria facial E-CPF não aprovada', 'Busque um fundo neutro e boa iluminação.', 3, 13),
('Erro no app de biometria E-CPF', 'Atualize o aplicativo na loja do seu celular.', 3, 13),
('QR Code da biometria E-CPF inválido', 'Gere um novo QR Code no sistema.', 3, 13),
('Biometria E-CPF expirou', 'Refaça a captura biométrica.', 3, 13),
('Como validar biometria E-CPF?', 'Siga o passo a passo enviado por e-mail.', 3, 13),
('Biometria facial E-CNPJ não aprovada', 'Busque um fundo neutro e boa iluminação.', 3, 14),
('Erro no app de biometria E-CNPJ', 'Atualize o aplicativo na loja do seu celular.', 3, 14),
('QR Code da biometria E-CNPJ inválido', 'Gere um novo QR Code no sistema.', 3, 14),
('Biometria E-CNPJ expirou', 'Refaça a captura biométrica.', 3, 14),
('Como validar biometria E-CNPJ?', 'Siga o passo a passo enviado por e-mail.', 3, 14),
('Retentativa biométrica E-CPF falhou', 'Siga atentamente as orientações de enquadramento.', 3, 15),
('Não recebi SMS de retentativa E-CPF', 'Verifique se o número cadastrado está correto.', 3, 15),
('Link de retentativa E-CPF quebrado', 'Copie e cole o link diretamente no navegador.', 3, 15),
('Sistema indisponível para retentativa E-CPF', 'Tente novamente em algumas horas.', 3, 15),
('Documento rejeitado na retentativa E-CPF', 'Envie um documento sem reflexos ou cortes.', 3, 15),
('Retentativa biométrica E-CNPJ falhou', 'Siga atentamente as orientações de enquadramento.', 3, 16),
('Não recebi SMS de retentativa E-CNPJ', 'Verifique se o número cadastrado está correto.', 3, 16),
('Link de retentativa E-CNPJ quebrado', 'Copie e cole o link diretamente no navegador.', 3, 16),
('Sistema indisponível para retentativa E-CNPJ', 'Tente novamente em algumas horas.', 3, 16),
('Documento rejeitado na retentativa E-CNPJ', 'Envie o contrato social assinado e legível.', 3, 16),


('Dificuldade em anexar CNH', 'O arquivo deve estar no formato PDF ou JPG.', 1, 2),
('RG não aceito no sistema', 'Documentos com mais de 10 anos não são válidos.', 1, 2),
('Não consigo assinar o termo', 'Instale a extensão de assinatura digital no navegador.', 1, 3),
('Onde baixo o manual do usuário?', 'O manual está disponível na aba de Ajuda.', 1, 1),
('Minha internet caiu no meio da validação', 'Retome o processo de onde parou usando seu CPF.', 1, 2),
('O agente não consegue me ouvir', 'Verifique as permissões de microfone do Windows.', 2, 7),
('Imagem embaçada na conferência', 'Limpe a lente da sua webcam.', 2, 8),
('Como troco de dispositivo durante a chamada?', 'Saia da sala atual e acesse o link no novo aparelho.', 2, 7),
('Posso fazer a conferência pelo celular?', 'Sim, através do nosso aplicativo oficial.', 2, 8),
('O que é o PIN e PUK?', 'São senhas de segurança do seu certificado. Não as perca.', 3, 11),
('Bloqueei meu PUK, o que fazer?', 'Infelizmente o certificado foi perdido. É necessário um novo.', 3, 12),
('O Leitor de mesa parou de funcionar', 'Teste em outro computador ou atualize os drivers.', 3, 11),
('O sistema acusa token não formatado', 'Não formate o token! Contate o suporte imediatamente.', 3, 12),
('Posso usar o E-CPF no Mac?', 'Sim, baixe os drivers específicos para MacOS.', 3, 11),
('Meu antivírus bloqueou a biometria', 'Pause o antivírus temporariamente durante o processo.', 3, 14),
('Erro de permissão no Windows', 'Conceda privilégios de administrador ao aplicativo.', 3, 13),
('Como saber se a validação deu certo?', 'Você receberá um e-mail de confirmação em até 1 hora.', 1, 1),
('Preciso de atendimento em libras', 'Agende um horário específico para atendimento acessível.', 2, 7),
('Qual o horário de funcionamento do suporte?', 'Nosso suporte funciona das 08h às 18h.', 1, 2),
('Onde acho a chave de acesso?', 'A chave foi enviada por SMS no momento da compra.', 1, 3);