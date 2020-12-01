# noinspection SqlNoDataSourceInspectionForFile

USE `task`;
CREATE TABLE `task`
(
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(255)    NOT NULL DEFAULT '',
    `description`  LONGTEXT        NULL,
    `due_date`     DATETIME        NOT NULL,
    `created_date` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_date` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `index_title` (`title`(100)),
    INDEX `index_due_date` (`due_date`)
)
    COLLATE = 'utf8mb4_general_ci'
;

ALTER TABLE `task`
    ADD COLUMN `completed` TINYINT NOT NULL DEFAULT '0' AFTER `due_date`,
    ADD INDEX `index_completed` (`completed`);

