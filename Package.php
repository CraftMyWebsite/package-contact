<?php

namespace CMW\Package\Contact;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return 'Contact';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function authors(): array
    {
        return ['Teyir'];
    }

    public function isGame(): bool
    {
        return false;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                icon: 'fas fa-address-book',
                title: LangManager::translate('contact.contact'),
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: LangManager::translate('contact.menu.setting'),
                        permission: 'contact.settings',
                        url: 'contact/settings',
                        subMenus: []
                    ),
                    new PackageSubMenuType(
                        title: LangManager::translate('contact.menu.history'),
                        permission: 'contact.history',
                        url: 'contact/history',
                        subMenus: []
                    ),
                    new PackageSubMenuType(
                        title: LangManager::translate('contact.menu.stats'),
                        permission: 'contact.stats',
                        url: 'contact/stats',
                        subMenus: []
                    ),
                ]
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }
}
