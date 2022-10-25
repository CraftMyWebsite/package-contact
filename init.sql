CREATE TABLE IF NOT EXISTS `cmw_contact_settings`
(
    `contact_settings_captcha`           TINYINT      NOT NULL,
    `contact_settings_email`             VARCHAR(100) NULL,
    `contact_settings_mail_confirmation` MEDIUMTEXT   NULL
) ENGINE = InnoDB
  charset = utf8mb4;

INSERT INTO cmw_contact_settings (contact_settings_captcha)
VALUES (0);