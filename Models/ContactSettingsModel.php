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

        return new ContactSettingsEntity(
            $res['contact_settings_email'],
            $res['contact_settings_object_confirmation'],
            $res['contact_settings_mail_confirmation']
        );
    }

    public function updateConfig(?string $email, ?string $object = null, ?string $mail = null): ?ContactSettingsEntity
    {
        $info = array(
            "email" => $email,
            "object" => $object,
            "mail" => $mail,
        );

        $sql = "UPDATE cmw_contact_settings SET contact_settings_email = :email,
                        contact_settings_object_confirmation = :object, contact_settings_mail_confirmation = :mail";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getConfig();
        }

        return null;
    }

}
