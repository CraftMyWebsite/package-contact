CREATE TABLE IF NOT EXISTS `cmw_contact_settings`
(
    `contact_settings_captcha`             TINYINT      NOT NULL,
    `contact_settings_email`               VARCHAR(100) NULL,
    `contact_settings_object_confirmation` varchar(255) null,
    `contact_settings_mail_confirmation`   MEDIUMTEXT   NULL
) ENGINE = InnoDB
  charset = utf8mb4;

INSERT INTO cmw_contact_settings (contact_settings_captcha)
VALUES (0);

CREATE TABLE IF NOT EXISTS `cmw2`.`cmw_contact`
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
  charset = utf8mb4;