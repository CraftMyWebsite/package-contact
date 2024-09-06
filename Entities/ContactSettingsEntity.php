<?php

namespace CMW\Entity\Contact;

class ContactSettingsEntity
{
    private ?string $email;
    private ?string $objectConfirmation;
    private ?string $mailConfirmation;
    private bool $antiSpam;

    /**
     * @param ?string $email
     * @param string|null $objectConfirmation
     * @param ?string $mailConfirmation
     * @param bool $antiSpam
     */
    public function __construct(?string $email, ?string $objectConfirmation, ?string $mailConfirmation, bool $antiSpam)
    {
        $this->email = $email;
        $this->objectConfirmation = $objectConfirmation;
        $this->mailConfirmation = $mailConfirmation;
        $this->antiSpam = $antiSpam;
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

    /**
     * @return bool
     */
    public function getAntiSpamActive(): bool
    {
        return $this->antiSpam;
    }
}
