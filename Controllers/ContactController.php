<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Model\Users\UsersModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
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

    #[NoReturn] #[Link("/settings", Link::POST, [], "/cmw-admin/contact")]
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
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css",
                "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->addVariableList(["messages" => $messages])
            ->view();
    }

    #[NoReturn] #[Link("/history/deleteSelected", Link::POST, [], "/cmw-admin/contact")]
    private function adminDeleteSelectedPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        $messageIds = $_POST['messageIds'];

        if (empty($messageIds)) {
            Flash::send(Alert::ERROR, "Contact", "Aucun message sélectionné");
            Redirect::redirectPreviousRoute();
        }

        $i = 0;
        foreach ($messageIds as $messageId) {
            $messageId = FilterManager::filterData($messageId, 11, FILTER_SANITIZE_NUMBER_INT);
            ContactModel::getInstance()->deleteMessage($messageId);
            $i++;
        }
        Flash::send(Alert::SUCCESS, "Contact", "$i message supprimé !");

        Redirect::redirect("cmw-admin/contact/history");
    }

    #[Link("/read/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    private function adminContactRead(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.history");

        $message = contactModel::getInstance()->getMessageById($id);
        $userId = UsersModel::getCurrentUser()?->getId();

        if (!is_null($userId) && !$message?->isRead()) {
            contactModel::getInstance()->setMessageState($id, $userId);
        }

        View::createAdminView('Contact', 'read')
            ->addScriptAfter("App/Package/Contact/Views/Resources/Js/print.js")
            ->addVariableList(["message" => $message])
            ->view();
    }

    #[NoReturn] #[Link("/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    private function adminContactDelete(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.delete");

        if (contactModel::getInstance()->deleteMessage($id)) {
            Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
                LangManager::translate("contact.toaster.delete.success"));
        } else {
            Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
                LangManager::translate("contact.toaster.delete.error"));
        }

        Redirect::redirect("cmw-admin/contact/history");
    }

    #[Link("/stats", Link::GET, [], "/cmw-admin/contact")]
    private function adminContactStats(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.stats");

        View::createAdminView('Contact', 'stats')
            ->view();
    }
}
