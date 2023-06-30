<?php

return [
    "contact" => "Contact",
    "settings" => [
        "title" => "Paramètres contact",
        "description" => "Gérez les paramètres de vos formulaires de contact",
        "email" => "Adresse mail de réception",
        "captcha_hint" => "Activer le captcha ? (Paramétrable <a href='../security'>ici</a>)",
        "object_confirmation" => "Objet du mail de confirmation",
        "object_confirmation_placeholder" => "Merci de nous avoir envoyé un message !",
        "mail_confirmation" => "Mail de confirmation d'envoi",
    ],
    "message" => [
        "state" => "État",
        "read" => "Lire",
        "id" => "Numéro",
        "email" => "Email",
        "name" => "Nom",
        "object" => "Objet",
        "content" => "Contenu",
        "date" => "Date",
        "readed" => "<span class='text-success'><i class='fa-solid fa-eye'></i> Lue</span>",
        "notread" => "<span class='text-warning'><i class='fa-solid fa-eye-slash'></i> Non lue</span>",
        "delete" => "Supprimer",
        "deletetitle" => "Voulez-vous supprimer ce message ?",
        "deletealert" => "La suppression de ce message est définitive !<br>Aucun retour possible !",
    ],
    "mail" => [
        "object" => " Formulaire de contact",
        "mail" => "Mail: ",
        "name" => "<br>Nom: ",
        "object_sender" => "<br>Objet: ",
        "content" => "<br>Contenue: ",
    ],
    "history" => [
        "title" => "Historique des messages",
        "description" => "Affichez la liste de vos messages reçus",
    ],
    "button" => [
        "download" => "Télécharger",
    ],
    "read" => [
        "title" => "Lecture d'un message",
        "description" => "Lisez un message",
        "informations" => "Informations",
        "back" => "<i class='fa-solid fa-arrow-left'></i> Retourner vers l'historique",
        "message" => "Message :",
    ],
    "toaster" => [
        "send" => [
            "success" => "Message envoyé avec succès !",
            "error-captcha" => "Merci de compléter le captcha",
            "errorFillFields" => "Merci de remplir tous les champs",
        ],
        "delete" => [
            "success" => "Message supprimé !",
        ],
        "error" => [
            "notConfigured" => "Merci de correctement configurer le package Contact."
        ],
    ],
];
