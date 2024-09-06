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
    public function getMessageById(int $id, #[ExpectedValues(['READ', 'UNREAD'])] ?string $filter = null): ?ContactEntity
    {
        $sql = 'SELECT * FROM cmw_contact WHERE contact_id = :id';

        if ($filter === 'READ') {
            $sql .= 'AND contact_is_read = 1';
        } else if ($filter === 'UNREAD') {
            $sql .= 'AND contact_is_read = 0';
        }

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(['id' => $id])) {
            return null;
        }

        $res = $res->fetch();

        if (!$res) {
            return null;
        }

        return new ContactEntity(
            $res['contact_id'],
            $res['contact_email'],
            $res['contact_name'],
            $res['contact_object'],
            $res['contact_content'],
            $res['contact_date'],
            $res['contact_first_reader'],
            $res['contact_is_spam']
        );
    }

    /**
     * @return \CMW\Entity\Pages\PageEntity[]
     */
    public function getMessages(#[ExpectedValues(['READ', 'UNREAD'])] ?string $filter = null): array
    {
        $sql = 'SELECT contact_id FROM cmw_contact ORDER BY contact_date DESC';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($message = $res->fetch()) {
            $toReturn[] = $this->getMessageById($message['contact_id'], $filter);
        }

        return $toReturn;
    }

    public function addMessage(string $email, string $name, string $object, string $content, int $isSpam): ?ContactEntity
    {
        $var = [
            'email' => $email,
            'name' => $name,
            'object' => $object,
            'content' => $content,
            'isSpam' => $isSpam,
        ];

        $sql = 'INSERT INTO cmw_contact (contact_email, contact_name, contact_object, contact_content, contact_is_spam)
                    VALUES (:email, :name, :object, :content, :isSpam)';

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute($var)) {
            return null;
        }

        return $this->getMessageById($db->lastInsertId());
    }

    public function deleteMessage(int $id): bool
    {
        $sql = 'DELETE FROM cmw_contact WHERE contact_id=:id';

        $db = DatabaseManager::getInstance();
        return $db->prepare($sql)->execute(['id' => $id]);
    }

    public function setMessageState(int $id, int $userId): void
    {
        $sql = 'UPDATE cmw_contact SET contact_first_reader = :userId WHERE contact_id = :id';

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);
        $res->execute(['userId' => $userId, 'id' => $id]);
    }

    public function setSpam(int $id, int $isSpam): void
    {
        $sql = 'UPDATE cmw_contact SET contact_is_spam = :isSpam WHERE contact_id = :id';

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);
        $res->execute(['id' => $id, 'isSpam' => $isSpam]);
    }

    public function countUnreadNonSpam(): int
    {
        $sql = 'SELECT COUNT(*) AS unread_non_spam FROM cmw_contact WHERE contact_first_reader IS NULL AND contact_is_spam = 0';

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);

        if (!$req->execute()) {
            return 0;
        }

        $res = $req->fetch();

        if (!$res) {
            return 0;
        }

        return $res['unread_non_spam'] ?? 0;
    }

    public function countUnreadSpam(): int
    {
        $sql = 'SELECT COUNT(*) AS unread_spam FROM cmw_contact WHERE contact_first_reader IS NULL AND contact_is_spam = 1';

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);

        if (!$req->execute()) {
            return 0;
        }

        $res = $req->fetch();

        if (!$res) {
            return 0;
        }

        return $res['unread_spam'] ?? 0;
    }
}
