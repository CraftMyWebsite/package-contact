<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Core\MailController;
use CMW\Controller\Core\SecurityController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Requests\Request;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Router\Link;
use CMW\Utils\Response;
use CMW\Utils\Utils;
use CMW\Manager\Views\View;
use CMW\Utils\Redirect;

/**
 * Class: @ContactController
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactController extends CoreController
{

    private ContactModel $contactModel;
    private ContactSettingsModel $contactSettingsModel;

    public function __construct()
    {
        parent::__construct();
        $this->contactModel = new ContactModel();
        $this->contactSettingsModel = new ContactSettingsModel();
    }

    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/contact")]
    #[Link("/settings", Link::GET, [], "/cmw-admin/contact")]
    public function adminContactSettings(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        $config = $this->contactSettingsModel->getConfig();

        View::createAdminView('contact', 'settings')
            ->addStyle("App/Package/wiki/Views/Assets/Css/main.css","Admin/Resources/Vendors/Summernote/summernote-lite.css","Admin/Resources/Assets/Css/Pages/summernote.css")
            ->addScriptAfter("Admin/Resources/Vendors/jquery/jquery.min.js","Admin/Resources/Vendors/Summernote/summernote-lite.min.js","Admin/Resources/Assets/Js/Pages/summernote.js")
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/settings", Link::POST, [], "/cmw-admin/contact")]
    public function adminContactSettingsPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        [$captcha, $email, $object, $mail] = Utils::filterInput("captcha", "email", "object", "mail");

        $this->contactSettingsModel->updateConfig($captcha === NULL ? 0 : 1, $email ?? null, $object, $mail);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("core.toaster.config.success"));

        Redirect::redirectToPreviousPage();
    }

    #[Link("/history", Link::GET, [], "/cmw-admin/contact")]
    public function adminContactHistory(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.history");

        $messages = $this->contactModel->getMessages();

        View::createAdminView('contact', 'history')
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css","Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("App/Package/contact/Views/Resources/Js/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->addVariableList(["messages" => $messages])
            ->view();
    }

    #[Link("/read/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    public function adminContactRead(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.history");

        $message = $this->contactModel->getMessageById($id);

        if (!$message?->isRead()) {
            $this->contactModel->setMessageState($id);
        }

        View::createAdminView('contact', 'read')
            ->addScriptAfter("App/Package/contact/Views/Resources/Js/main.js")
            ->addVariableList(["message" => $message])
            ->view();
    }

    #[Link("/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    public function adminContactDelete(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.delete");

        $this->contactModel->deleteMessage($id);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("contact.toaster.delete.success"));

        header("location: ../history");
    }


    /* PUBLIC AREA */

    #[Link("/", Link::GET, [], "/contact")]
    public function publicContact(): void
    {
        View::basicPublicView("contact", "main");
    }

    #[Link("/", Link::POST, [], "/contact")]
    public function publicContactPost(): void
    {
        $config = $this->contactSettingsModel->getConfig();

        if ($config?->captchaIsEnable()) {
            if (SecurityController::checkCaptcha()) {
                [$email, $name, $object, $content] = Utils::filterInput("email", "name", "object", "content");
                $this->contactModel->addMessage($email, $name, $object, $content);

                //Send mail confirmation
                (new MailController())
                    ->sendMail($email, $config?->getObjectConfirmation(), $config?->getMailConfirmation());

                Response::sendAlert("success", LangManager::translate("core.toaster.success"),
                    LangManager::translate("contact.toaster.send.success"));

            } else {
                Response::sendAlert("error", LangManager::translate("core.toaster.error"),
                    LangManager::translate("contact.toaster.send.error-captcha"));
            }
        } else {
            [$email, $name, $object, $content] = Utils::filterInput("email", "name", "object", "content");
            $this->contactModel->addMessage($email, $name, $object, $content);

            //Send mail confirmation
            (new MailController())
                ->sendMail($email, $config?->getObjectConfirmation(), $config?->getMailConfirmation());

            Response::sendAlert("success", LangManager::translate("core.toaster.success"),
                LangManager::translate("contact.toaster.send.success"));

        }
        header("location: " . $_SERVER['HTTP_REFERER']);

    }

}
