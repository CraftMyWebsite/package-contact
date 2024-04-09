<?php

namespace CMW\Model\Contact;

use CMW\Entity\Contact\ContactEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * Class: @ContactModel
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactModel extends AbstractModel
{

    public function getMessageById(int $id, #[ExpectedValues(["READ", "UNREAD"])] ?string $filter = null): ?ContactEntity
    {

        $sql = "SELECT contact_id, contact_email, contact_name, contact_object, contact_content, contact_date, contact_first_reader FROM cmw_contact WHERE contact_id = :id";

        if ($filter === "READ") {
            $sql .= "AND contact_is_read = 1";
        } else if ($filter === "UNREAD") {
            $sql .= "AND contact_is_read = 0";
        }

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(["id" => $id])) {
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
            $res['contact_first_reader']
        );
    }


    /**
     * @return \CMW\Entity\Pages\PageEntity[]
     */
    public function getMessages(#[ExpectedValues(["READ", "UNREAD"])] ?string $filter = null): array
    {

        $sql = "SELECT contact_id FROM cmw_contact ORDER BY contact_date DESC";
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($message = $res->fetch()) {
            $toReturn[] = $this->getMessageById($message["contact_id"], $filter);
        }

        return $toReturn;
    }

    public function addMessage(string $email, string $name, string $object, string $content): ?ContactEntity
    {
        $var = [
            "email" => $email,
            "name" => $name,
            "object" => $object,
            "content" => $content,
        ];

        $sql = "INSERT INTO cmw_contact (contact_email, contact_name, contact_object, contact_content)
                    VALUES (:email, :name, :object, :content)";

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if ($res->execute($var)) {
            return $this->getMessageById($db->lastInsertId());
        }

        return null;
    }

    public function deleteMessage(int $id): void
    {
        $sql = "DELETE FROM cmw_contact WHERE contact_id=:id";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(["id" => $id]);
    }

    public function setMessageState(int $id, int $userId): void
    {
        $sql = "UPDATE cmw_contact SET contact_first_reader = :userId WHERE contact_id = :id";

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);
        $res->execute(['userId' => $userId, "id" => $id]);
    }

}