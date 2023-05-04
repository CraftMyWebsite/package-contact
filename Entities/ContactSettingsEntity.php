<?php

namespace CMW\Entity\Contact;

class ContactSettingsEntity
{

    private bool $captcha;
    private ?string $email;
    private ?string $objectConfirmation;
    private ?string $mailConfirmation;

    /**
     * @param bool $captcha
     * @param ?string $email
     * @param string|null $objectConfirmation
     * @param ?string $mailConfirmation
     */
    public function __construct(bool $captcha, ?string $email,?string $objectConfirmation, ?string $mailConfirmation)
    {
        $this->captcha = $captcha;
        $this->email = $email;
        $this->objectConfirmation = $objectConfirmation;
        $this->mailConfirmation = $mailConfirmation;
    }

    /**
     * @return bool
     */
    public function captchaIsEnable(): bool
    {
        return $this->captcha;
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