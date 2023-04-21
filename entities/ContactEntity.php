<?php

namespace CMW\Entity\Contact;

use CMW\Controller\Core\CoreController;

class ContactEntity
{

    private string $id;
    private string $email;
    private string $name;
    private string $object;
    private string $content;
    private string $date;
    private bool $isRead;

    /**
     * @param int $id ;
     * @param string $email
     * @param string $name
     * @param string $object
     * @param string $content
     * @param string $date
     * @param bool $isRead
     */
    public function __construct(int $id, string $email, string $name, string $object, string $content, string $date, bool $isRead)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->object = $object;
        $this->content = $content;
        $this->date = $date;
        $this->isRead = $isRead;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return CoreController::formatDate($this->date);
    }

    /**
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->isRead;
    }

}