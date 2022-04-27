-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.7.26 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.2.0.6213
-- --------------------------------------------------------

INSERT INTO `permissao` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Aluno', NULL, NULL),
	(2, 'Professor', NULL, NULL),
	(3, 'Monitor', NULL, NULL),
	(4, 'Funcionário', NULL, NULL);

INSERT INTO `status_baia` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Livre', NULL, NULL),
	(2, 'Em uso', NULL, NULL),
	(3, 'Bloqueada', NULL, NULL),
	(4, 'Vazia', NULL, NULL),
	(5, 'Em manutenção', NULL, NULL),
	(6, 'Em checagem', NULL, NULL);

INSERT INTO `status_equipamento` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Livre', NULL, NULL),
	(2, 'Em uso', NULL, NULL),
	(3, 'Bloqueado', NULL, NULL),
	(4, 'Emprestado', NULL, NULL),
	(5, 'Reservado', NULL, NULL),
	(6, 'Em manutenção', NULL, NULL),
	(7, 'Ausente', NULL, NULL);

INSERT INTO `status_reserva` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Liberada', NULL, NULL),
	(2, 'Cancelada', NULL, NULL),
	(3, 'Pendente', NULL, NULL);

INSERT INTO `tipo_equipamento` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Fonte', NULL, NULL),
	(2, 'Multímetro', NULL, NULL),
	(3, 'Osciloscópio', NULL, NULL);

INSERT INTO `tipo_evento` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Erro', NULL, NULL),
	(2, 'Registro', NULL, NULL),
	(3, 'Uso', NULL, NULL),
	(4, 'Atualização', NULL, NULL);

INSERT INTO `status_user` (`id`, `descricao`, `created_at`, `updated_at`) VALUES
	(1, 'Liberado', NULL, NULL),
	(2, 'Bloqueado', NULL, NULL);

INSERT INTO `user` (`id`, `matricula`, `nome`, `rfid`, `disciplina_monitor`, `email`, `email_verified_at`, `password`, `remember_token`, `permissao_id`, `status_id`, `created_at`, `updated_at`) VALUES
	(1, '10314910409', 'Pedro Vinícius Batista Clericuzi', '186195356', 'Testes', 'pvbc@cin.ufpe.br', NULL, '$2y$10$qYAV5HzkFuARPOVs/VWzWuaoGpns0PpOanHJU9iEYJLqpYYxbcxGq', NULL, 3, 1, '2021-03-22 21:04:53', '2021-03-30 23:44:24'),
	(2, '10314910410', 'Lucas Amorim', 'X4X2X3', '', 'lucas@cin.ufpe.br', NULL, '$2y$10$wgtBUStfJEtFk0LZhrRCtuaXN73LD7s3ODM76QQHzDk7ha9LFzJde', NULL, 1, 1, '2021-03-22 21:04:53', '2021-04-21 20:45:20'),
	(3, '10314910411', 'Edna Barros', 'X4X5X3', '', 'edna@cin.ufpe.br', NULL, '$2y$10$GB.57JZMg7BlRQ6fbxU2e.vLWzGYGf2g/hhGK0oW24MDoDR4yqH02', NULL, 2, 1, '2021-03-22 21:04:53', '2021-03-22 21:04:53');
     
INSERT INTO `baia` (`id`, `num_baia`, `user_usando`, `status_baia_id`, `created_at`, `updated_at`) VALUES
	(3, 1, '', 3, '2021-03-11 05:13:01', '2021-03-18 15:35:33'),
	(4, 2, '', 4, '2021-03-12 01:45:28', '2021-03-12 01:45:28'),
	(5, 3, '', 4, '2021-03-12 01:56:40', '2021-03-12 01:56:40'),
	(6, 4, '', 1, '2021-03-11 23:03:58', '2021-03-11 23:03:58'),
	(7, 5, '', 1, '2021-03-12 01:16:25', '2021-03-12 01:16:25'),
	(8, 6, '', 2, '2021-03-12 01:16:59', '2021-03-12 01:16:59');

INSERT INTO `reserva` (`id`, `cpf`, `justificativa`, `observacoes`, `data`, `hora`, `baia_id`, `status_id`, `created_at`, `updated_at`) VALUES
	(4, '10314910410', 'Nada', 'Teste', '2021-04-15', '22:03:00', 5, 2, '2021-04-08 12:15:19', '2021-04-22 15:36:23'),
	(8, '10314910410', 'jjj', 'Testetetete', '2021-04-30', '05:55:00', 5, 1, '2021-04-15 15:23:47', '2021-04-22 15:40:28'),
	(9, '10314910409', 'Nada', NULL, '2021-05-07', '18:00:00', 7, 3, '2021-04-20 00:34:44', '2021-04-20 00:34:44'),
	(12, '10314910410', 'Teste', NULL, '2021-04-26', '22:00:00', 3, 1, '2021-04-22 02:43:10', '2021-04-26 02:38:24'),
	(14, '10314910409', 'teste', NULL, '2021-04-30', '00:00:00', 7, 3, '2021-04-26 00:05:19', '2021-04-26 00:05:19');

INSERT INTO `change_log` (`id`, `mat_aluno`, `mat_monitor`, `mensagem`, `baia_id`, `reserva_id`, `equipamento_id`, `evento_id`, `created_at`, `updated_at`) VALUES
	(1, NULL, '10314910409', 'Solicitação aceita', NULL, '12', NULL, 4, '2021-04-26 02:10:12', '2021-04-26 02:10:12'),
	(2, NULL, '10314910409', 'Solicitação aceita', NULL, '12', NULL, 4, '2021-04-26 02:38:24', '2021-04-26 02:38:24'),
	(3, '10314910410', '10314910409', 'Uso indevido da baia', 4, NULL, NULL, 3, '2021-04-26 02:38:24', '2021-05-05 03:03:52'),
	(4, '10314910410', NULL, 'Uso do equipamento', NULL, NULL, 4, 3, '2021-05-05 03:04:37', '2021-05-05 03:04:39');

INSERT INTO `equipamento` (`id`, `uuid_tag`, `marca`, `modelo`, `tombamento`, `estado_conserv`, `baia_id`, `tipo_id`, `status_id`, `created_at`, `updated_at`) VALUES
	(2, 'AV45RD90', 'Multilaser', 'HBX 120v', NULL, NULL, 4, 2, 2, '2021-03-19 02:11:52', '2021-03-19 02:11:52'),
	(3, 'AS60CV69', 'Minipa', 'M16220', NULL, 'Usado, em bom estado', 3, 3, 1, '2021-03-22 01:20:25', '2021-03-22 01:20:25'),
	(4, 'X3FSE5', 'Multilaser', 'M16220', '85975', 'Em bom estado', 6, 1, 1, '2021-03-31 00:14:58', '2021-03-31 00:14:58');