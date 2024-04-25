<?php

namespace CMW\Implementation\Contact\Core;

use CMW\Interface\Core\IMenus;
use CMW\Manager\Lang\LangManager;

class ContactMenusImplementations implements IMenus {

    public function getRoutes(): array
    {
        return [
            LangManager::translate('contact.contact') => 'contact'
        ];
    }

    public function getPackageName(): string
    {
        return 'Contact';
    }
}