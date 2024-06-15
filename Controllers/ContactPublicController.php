<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\MailController;
use CMW\Controller\Core\SecurityController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use CMW\Utils\Website;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @ContactPublicController
 * @package Contact
 * @author Teyir
 * @version 1.0
 */
class ContactPublicController extends AbstractController
{

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