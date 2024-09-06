<?php

namespace CMW\Permissions\Contact;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'contact.settings',
                description: LangManager::translate('contact.permissions.contact.settings'),
            ),
            new PermissionInitType(
                code: 'contact.history',
                description: LangManager::translate('contact.permissions.contact.history'),
            ),
            new PermissionInitType(
                code: 'contact.delete',
                description: LangManager::translate('contact.permissions.contact.delete'),
            ),
            new PermissionInitType(
                code: 'contact.stats',
                description: LangManager::translate('contact.permissions.contact.stats'),
            ),
        ];
    }
}
