<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Users\UsersController;
use CMW\Model\Contact\ContactModel;
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

    public function __construct()
    {
        parent::__construct();
        $this->contactModel = new ContactModel();
    }

    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/contact")]
    #[Link("/settings", Link::GET, [], "/cmw-admin/contact")]
    public function adminContactSettings(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "contact.settings");

        $config = $this->contactModel->getConfig();

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


        $this->contactModel->updateConfig($captcha === NULL ? 0 : 1, $email ?? null, $mail);

        header("Location: settings");
    }

}
