<?php

return [
    "contact" => "Contact",
    "antispam" => [
        "title" => "Anti Spam",
        "description" => "Sorry you don't respect our usage policy!",
        "setting" => "Enable anti-spam",
        "inbox" => "Inbox",
        "junk" => "Junk",
    ],
    "settings" => [
        "title" => "Contact settings",
        "description" => "Manage your contact form settings",
        "email" => "Receiving email address",
        "captcha_hint" => "Enable captcha? (Configurable <a href='../security'>here</a>)",
        "object_confirmation" => "Subject of the confirmation email",
        "object_confirmation_placeholder" => "Thanks for sending us a message!",
        "mail_confirmation" => "Delivery confirmation email",
    ],
    "message" => [
        "state" => "State",
        "read" => "Read",
        "id" => "Number",
        "email" => "Email",
        "name" => "Name",
        "object" => "Object",
        "content" => "Contents",
        "date" => "Date",
        "readed" => "<span class='text-success'><i class='fa-solid fa-eye'></i> Read</span>",
        "notread" => "<span class='text-warning'><i class='fa-solid fa-eye-slash'></i> Not read</span>",
        "delete" => "Remove",
        "deletetitle" => "Do you want to delete this message?",
        "deletealert" => "Deletion of this message is permanent !<br>No return possible !",
    ],
    "mail" => [
        "object" => " Contact form",
        "mail" => "Mail: ",
        "name" => "<br>Name: ",
        "object_sender" => "<br>Object: ",
        "content" => "<br>Content: ",
    ],
    "history" => [
        "title" => "Message history",
        "description" => "View the list of your received messages",
    ],
    "button" => [
        "download" => "Download",
    ],
    "read" => [
        "title" => "Reading a message",
        "description" => "Read a message",
        "informations" => "Informations",
        "back" => "<i class='fa-solid fa-arrow-left'></i> Return to history",
        "message" => "Message :",
    ],
    "toaster" => [
        "send" => [
            "success" => "Message sent successfully !",
            "error-captcha" => "Please complete the captcha.",
            "errorFillFields" => "Please fill all inputs",
        ],
        "delete" => [
            "success" => "Message deleted !",
            "error" => "Unable to delete this message !",
        ],
        "error" => [
            "notConfigured" => "Please configure your Contact package.",
        ],
    ],
    "permissions" => [
        "contact" => [
            "settings" => "Edit settings",
            "history" => "Show history",
            "delete" => "Delete messages",
            "stats" => "Show stats",
        ],
    ],
];
