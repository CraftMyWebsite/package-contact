<?php

namespace CMW\Model\Contact;

use CMW\Entity\Contact\ContactSettingsEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;

/**
 * Class: @ContactSettingsModel
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactSettingsModel extends AbstractModel
{
    public function getConfig(): ?ContactSettingsEntity
    {
        $sql = "SELECT * FROM cmw_contact_settings LIMIT 1";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);


        if (!$res->execute()) {
            return null;
        }

        $res = $res->fetch();

        if (!$res) {
            return null;
        }

        return new ContactSettingsEntity(
            $res['contact_settings_email'],
            $res['contact_settings_object_confirmation'],
            $res['contact_settings_mail_confirmation'],
            $res['contact_settings_anti_spam']
        );
    }

    public function updateConfig(?string $email, ?string $object = null, ?string $mail = null, int $antiSpam): ?ContactSettingsEntity
    {
        $info = [
            "email" => $email,
            "object" => $object,
            "mail" => $mail,
            "antiSpam" => $antiSpam,
        ];

        $sql = "UPDATE cmw_contact_settings SET contact_settings_email = :email,
                        contact_settings_object_confirmation = :object, contact_settings_mail_confirmation = :mail, contact_settings_anti_spam = :antiSpam";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if (!$req->execute($info)) {
            return null;
        }

        return $this->getConfig();
    }

}
