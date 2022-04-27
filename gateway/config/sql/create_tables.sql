USE labcin;

CREATE TABLE IF NOT EXISTS backup (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  tabela VARCHAR(255) NULL,
  updated_at TIMESTAMP NULL,
  PRIMARY KEY(id)
);

CREATE TABLE `status_baia` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7
;

CREATE TABLE `status_equipamento` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=8
;

CREATE TABLE `status_reserva` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;

CREATE TABLE `status_user` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;

CREATE TABLE `tipo_equipamento` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;

CREATE TABLE `tipo_evento` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;

CREATE TABLE `change_log` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mat_aluno` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`mat_monitor` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`mensagem` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`baia_id` INT(11) NULL DEFAULT NULL,
	`reserva_id` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`equipamento_id` INT(11) NULL DEFAULT NULL,
	`evento_id` BIGINT(20) UNSIGNED NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `change_log_evento_id_foreign` (`evento_id`) USING BTREE,
	CONSTRAINT `change_log_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `labcin`.`tipo_evento` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=83
;

CREATE TABLE `permissao` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`descricao` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;

CREATE TABLE `user` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`matricula` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`nome` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`rfid` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`disciplina_monitor` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`status_id` BIGINT(20) UNSIGNED NOT NULL,
	`email` VARCHAR(191) NOT NULL COLLATE 'utf8_general_ci',
	`email_verified_at` VARCHAR(191) NULL DEFAULT NULL,
	`password` VARCHAR(191) NOT NULL COLLATE 'utf8_general_ci',
	`remember_token` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`permissao_id` BIGINT(20) UNSIGNED NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `user_email_unique` (`email`) USING BTREE,
	INDEX `user_status_foreign` (`status_id`) USING BTREE,
	INDEX `user_permissao_id_foreign` (`permissao_id`) USING BTREE,
	CONSTRAINT `user_permissao_id_foreign` FOREIGN KEY (`permissao_id`) REFERENCES `labcin`.`permissao` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `user_status_foreign` FOREIGN KEY (`status_id`) REFERENCES `labcin`.`status_user` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;

CREATE TABLE `baia` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`num_baia` INT(11) NOT NULL,
	`status_baia_id` BIGINT(20) UNSIGNED NOT NULL,
	`user_usando` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `baia_status_baia_id_foreign` (`status_baia_id`) USING BTREE,
	CONSTRAINT `baia_status_baia_id_foreign` FOREIGN KEY (`status_baia_id`) REFERENCES `labcin`.`status_baia` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=9
;

CREATE TABLE `reserva` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cpf` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`justificativa` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`observacoes` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`data` DATE NULL DEFAULT NULL,
	`hora` TIME NULL DEFAULT NULL,
	`baia_id` BIGINT(20) UNSIGNED NOT NULL,
	`status_id` BIGINT(20) UNSIGNED NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `reserva_baia_id_foreign` (`baia_id`) USING BTREE,
	INDEX `reserva_status_id_foreign` (`status_id`) USING BTREE,
	CONSTRAINT `reserva_baia_id_foreign` FOREIGN KEY (`baia_id`) REFERENCES `labcin`.`baia` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `reserva_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `labcin`.`status_reserva` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=16
;

CREATE TABLE `equipamento` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uuid_tag` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`marca` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`modelo` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`tombamento` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`estado_conserv` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`baia_id` BIGINT(20) UNSIGNED NOT NULL,
	`tipo_id` BIGINT(20) UNSIGNED NOT NULL,
	`status_id` BIGINT(20) UNSIGNED NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `equipamento_baia_id_foreign` (`baia_id`) USING BTREE,
	INDEX `equipamento_tipo_id_foreign` (`tipo_id`) USING BTREE,
	INDEX `equipamento_status_id_foreign` (`status_id`) USING BTREE,
	CONSTRAINT `equipamento_baia_id_foreign` FOREIGN KEY (`baia_id`) REFERENCES `labcin`.`baia` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `equipamento_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `labcin`.`status_equipamento` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
	CONSTRAINT `equipamento_tipo_id_foreign` FOREIGN KEY (`tipo_id`) REFERENCES `labcin`.`tipo_equipamento` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;

CREATE TABLE IF NOT EXISTS registro_offline (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  requisicao VARCHAR(255) NULL,
  PRIMARY KEY(id)
);