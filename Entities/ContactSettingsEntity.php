<?php

namespace CMW\Entity\Contact;

class ContactSettingsEntity
{
    private ?string $email;
    private ?string $objectConfirmation;
    private ?string $mailConfirmation;

    /**
     * @param ?string $email
     * @param string|null $objectConfirmation
     * @param ?string $mailConfirmation
     */
    public function __construct(?string $email,?string $objectConfirmation, ?string $mailConfirmation)
    {
        $this->email = $email;
        $this->objectConfirmation = $objectConfirmation;
        $this->mailConfirmation = $mailConfirmation;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getObjectConfirmation(): ?string
    {
        return $this->objectConfirmation;
    }


    /**
     * @return string|null
     */
    public function getMailConfirmation(): ?string
    {
        return $this->mailConfirmation;
    }

}