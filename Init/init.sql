CREATE TABLE IF NOT EXISTS `cmw_contact_settings`
(
    `contact_settings_email`               VARCHAR(100) NULL,
    `contact_settings_object_confirmation` VARCHAR(255) NULL,
    `contact_settings_mail_confirmation`   MEDIUMTEXT   NULL,
    `contact_settings_anti_spam`          INT NOT NULL DEFAULT 1
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cmw_contact`
(
    `contact_id`           INT          NOT NULL AUTO_INCREMENT,
    `contact_email`        VARCHAR(100) NOT NULL,
    `contact_name`         VARCHAR(100) NOT NULL,
    `contact_object`       VARCHAR(255) NOT NULL,
    `contact_content`      MEDIUMTEXT   NOT NULL,
    `contact_date`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `contact_first_reader` INT          NULL     DEFAULT NULL,
    PRIMARY KEY (`contact_id`),
    CONSTRAINT `cmw_contact_ibfk_1` FOREIGN KEY (`contact_first_reader`)
        REFERENCES `cmw_users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


INSERT INTO `cmw_contact_settings` (`contact_settings_email`, `contact_settings_object_confirmation`,
                                    `contact_settings_mail_confirmation`, `contact_settings_anti_spam`)
VALUES (NULL, NULL, NULL, 1);