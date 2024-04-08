<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\MailController;
use CMW\Controller\Core\SecurityController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Manager\Router\Link;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Utils\Utils;
use CMW\Manager\Views\View;
use CMW\Utils\Redirect;
use CMW\Utils\Website;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @ContactController
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactController extends AbstractController
{
    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/contact")]
    #[Link("/settings", Link::GET, [], "/cmw-admin/contact")]
    private function adminContactSettings(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        $config = contactSettingsModel::getInstance()->getConfig();

        View::createAdminView('Contact', 'settings')
            ->addScriptBefore("Admin/Resources/Vendors/Tinymce/tinymce.min.js",
                "Admin/Resources/Vendors/Tinymce/Config/full_absolute_links.js")
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/settings", Link::POST, [], "/cmw-admin/contact")]
    private function adminContactSettingsPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        [$email, $object, $mail] = Utils::filterInput("email", "object", "mail");

        contactSettingsModel::getInstance()->updateConfig($email ?? null, $object, $mail);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("core.toaster.config.success"));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/history", Link::GET, [], "/cmw-admin/contact")]
    private function adminContactHistory(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.history");

        $messages = contactModel::getInstance()->getMessages();

        View::createAdminView('Contact', 'history')
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css", "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->addVariableList(["messages" => $messages])
            ->view();
    }

    #[Link("/read/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    private function adminContactRead(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.history");

        $message = contactModel::getInstance()->getMessageById($id);

        if (!$message?->isRead()) {
            contactModel::getInstance()->setMessageState($id);
        }

        View::createAdminView('Contact', 'read')
            ->addScriptAfter("App/Package/Contact/Views/Resources/Js/print.js")
            ->addVariableList(["message" => $message])
            ->view();
    }

    #[Link("/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    private function adminContactDelete(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.delete");

        contactModel::getInstance()->deleteMessage($id);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("contact.toaster.delete.success"));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/stats", Link::GET, [], "/cmw-admin/contact")]
    private function adminContactStats(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.stats");

        View::createAdminView('Contact', 'stats')
            ->view();
    }


    /* PUBLIC AREA */

    #[Link("/", Link::GET, [], "/contact")]
    private function publicContact(): void
    {
        View::basicPublicView("Contact", "main");
    }


    #[NoReturn] #[Link("/", Link::POST, [], "/contact")]
    private function publicContactPost(): void
    {
        [$email, $name, $object, $content] = Utils::filterInput("email", "name", "object", "content");

        $config = contactSettingsModel::getInstance()->getConfig();

        if ($config === null || $config->getEmail() === null) {
            Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.error.notConfigured'));
            Redirect::redirectPreviousRoute();
        }
        if (SecurityController::checkCaptcha()) {

            if (Utils::containsNullValue($email, $name, $object, $content)) {
                Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                    LangManager::translate('contact.toaster.send.errorFillFields'));
                Redirect::redirectPreviousRoute();
            }

            contactModel::getInstance()->addMessage($email, $name, $object, $content);

            MailController::getInstance()->sendMail($email, $config->getObjectConfirmation(), $config->getMailConfirmation());
            MailController::getInstance()->sendMail($config->getEmail(), "[" . Website::getWebsiteName() . "]" . LangManager::translate("contact.mail.object"), LangManager::translate("contact.mail.mail") . $email . LangManager::translate("contact.mail.name") . $name . LangManager::translate("contact.mail.object_sender") . $object . LangManager::translate("contact.mail.content") . $content);

            Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
                LangManager::translate("contact.toaster.send.success"));
        } else {
            Flash::send(Alert::ERROR, LangManager::translate("core.toaster.error"),
                LangManager::translate("contact.toaster.send.error-captcha"));
        }
        Redirect::redirectPreviousRoute();
    }

}
