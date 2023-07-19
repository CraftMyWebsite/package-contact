CREATE TABLE IF NOT EXISTS `cmw_contact_settings`
(
    `contact_settings_email`               VARCHAR(100) NULL,
    `contact_settings_object_confirmation` VARCHAR(255) NULL,
    `contact_settings_mail_confirmation`   MEDIUMTEXT   NULL
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cmw_contact`
(
    `contact_id`      INT          NOT NULL AUTO_INCREMENT,
    `contact_email`   VARCHAR(100) NOT NULL,
    `contact_name`    VARCHAR(100) NOT NULL,
    `contact_object`  VARCHAR(255) NOT NULL,
    `contact_content` MEDIUMTEXT   NOT NULL,
    `contact_date`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `contact_is_read` TINYINT      NOT NULL DEFAULT 0,
    PRIMARY KEY (`contact_id`)
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
