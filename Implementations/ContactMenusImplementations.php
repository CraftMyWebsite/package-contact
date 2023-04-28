<?php

namespace CMW\Implementation\Contact;

use CMW\Interface\Core\IMenus;

class ContactMenusImplementations implements IMenus {

    public function getRoutes(): array
    {
        return [
            'contact'
        ];
    }

    public function getPackageName(): string
    {
        return 'Contact';
    }
}