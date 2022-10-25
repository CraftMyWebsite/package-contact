<?php

namespace CMW\Model\Contact;

use CMW\Entity\Contact\ContactEntity;
use CMW\Manager\Database\DatabaseManager;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * Class: @ContactModel
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactModel extends DatabaseManager
{

    public function getMessageById(int $id, #[ExpectedValues(["READ", "UNREAD"])] ?string $filter = null): ?ContactEntity
    {

        $sql = "SELECT contact_id, contact_email, contact_name, contact_object, contact_content,
                DATE_FORMAT(contact_date, '%d/%m/%Y à %H:%i:%s') AS 'contact_date', contact_is_read
                FROM cmw_contact WHERE contact_id = :id";

        if ($filter === "READ") {
            $sql .= "AND contact_is_read = 1";
        } else if ($filter === "UNREAD") {
            $sql .= "AND contact_is_read = 0";
        }

        $db = self::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(array("id" => $id))) {
            return null;
        }

        $res = $res->fetch();

        return new ContactEntity(
            $res['contact_id'],
            $res['contact_email'],
            $res['contact_name'],
            $res['contact_object'],
            $res['contact_content'],
            $res['contact_date'],
            $res['contact_is_read']
        );
    }


    /**
     * @return \CMW\Entity\Pages\PageEntity[]
     */
    public function getMessages(#[ExpectedValues(["READ", "UNREAD"])] ?string $filter = null): array
    {

        $sql = "select contact_id from cmw_contact";
        $db = self::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($message = $res->fetch()) {
            $toReturn[] = $this->getMessageById($message["contact_id"], $filter);
        }

        return $toReturn;
    }

    public function addMessage(string $email, string $name, string $object, string $content): ?ContactEntity
    {
        $var = array(
            "email" => $email,
            "name" => $name,
            "object" => $object,
            "content" => $content,
        );

        $sql = "INSERT INTO cmw_contact (contact_email, contact_name, contact_object, contact_content)
                    VALUES (:email, :name, :object, :content)";

        $db = self::getInstance();

        $res = $db->prepare($sql);

        if ($res->execute($var)) {
            return $this->getMessageById($db->lastInsertId());
        }

        return null;
    }

    public function setMessageState(int $id): void
    {
        $sql = "UPDATE cmw_contact SET contact_is_read = 1 WHERE contact_id = :id";

        $db = self::getInstance();

        $res = $db->prepare($sql);
        $res->execute(["id" => $id]);
    }

}