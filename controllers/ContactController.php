<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Core\SecurityController;
use CMW\Controller\Users\UsersController;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\View;

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
            ->addScriptBefore("admin/resources/vendors/summernote/summernote.min.js",
                "admin/resources/vendors/summernote/summernote-bs4.min.js",
                "admin/resources/vendors/summernote/init.js")
            ->addStyle("admin/resources/vendors/summernote/summernote-bs4.min.css",
                "admin/resources/vendors/summernote/summernote.min.css")
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/settings", Link::POST, [], "/cmw-admin/contact")]
    public function adminContactSettingsPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        [$captcha, $email, $mail] = Utils::filterInput("captcha", "email", "mail");

        $this->contactSettingsModel->updateConfig($captcha === NULL ? 0 : 1, $email ?? null, $mail);

        header("Location: settings");
    }

    #[Link("/history", Link::GET, [], "/cmw-admin/contact")]
    public function adminContactHistory(): void
    {

        $messages = $this->contactModel->getMessages();

        View::createAdminView('contact', 'history')
            ->addVariableList(["messages" => $messages])
            ->view();
    }

    #[Link("/read/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/contact")]
    public function adminContactRead(int $id): void
    {

        $message = $this->contactModel->getMessageById($id);

        if(!$message?->isRead()){
            $this->contactModel->setMessageState($id);
        }

        View::createAdminView('contact', 'read')
            ->addScriptAfter("app/package/contact/views/resources/js/main.js")
            ->addVariableList(["message" => $message])
            ->view();
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

                //TODO TOASTER SUCCESS

                header("Location: /");
            }
        } else {
            [$email, $name, $object, $content] = Utils::filterInput("email", "name", "object", "content");
            $this->contactModel->addMessage($email, $name, $object, $content);

            //TODO TOASTER SUCCESS

            header("Location: /");
        }

    }

}
