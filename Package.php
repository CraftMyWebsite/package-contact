<?php

namespace CMW\Package\Contact;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return "Pages";
    }

    public function version(): string
    {
        return "1.0.0";
    }

    public function authors(): array
    {
        return ["Teyir"];
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
                lang: "fr",
                icon: "fas fa-address-book",
                title: "Contact",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Paramètres',
                        permission: 'contact.settings',
                        url: 'contact/settings',
                    ),
                    new PackageSubMenuType(
                        title: 'Historique',
                        permission: 'contact.history',
                        url: 'contact/history',
                    ),
                    new PackageSubMenuType(
                        title: 'Statistiques',
                        permission: 'contact.stats',
                        url: 'contact/stats',
                    ),
                ]
            ),
            new PackageMenuType(
                lang: "en",
                icon: "fas fa-address-book",
                title: "Contact",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Settings',
                        permission: 'contact.settings',
                        url: 'contact/settings',
                    ),
                    new PackageSubMenuType(
                        title: 'History',
                        permission: 'contact.history',
                        url: 'contact/history',
                    ),
                    new PackageSubMenuType(
                        title: 'Stats',
                        permission: 'contact.stats',
                        url: 'contact/stats',
                    ),
                ]
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ["Core"];
    }
}