<?php

namespace CMW\Controller\Contact\Public;

use CMW\Controller\Contact\ContactController;
use CMW\Controller\Core\SecurityController;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Mail\MailManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Security\EncryptManager;
use CMW\Manager\Views\View;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Utils\Redirect;
use CMW\Utils\Website;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @ContactPublicController
 * @package Contact
 */
class ContactPublicController extends AbstractController
{
    #[Link('/', Link::GET, [], '/contact')]
    private function publicContact(): void
    {
        View::createPublicView('Contact', 'main')->view();
    }

    #[NoReturn]
    #[Link('/', Link::POST, [], '/contact')]
    private function publicContactPost(): void
    {
        //Check captcha
        if (!SecurityController::checkCaptcha()) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('users.toaster.error'),
                LangManager::translate('users.security.captcha.invalid'),
            );
            Redirect::redirectPreviousRoute();
        }

        if (!isset($_POST['email'], $_POST['name'], $_POST['object'], $_POST['content'])) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.send.errorFillFields'),
            );
            Redirect::redirectPreviousRoute();
        }

        $email = FilterManager::filterInputStringPost('email', 100);
        $name = FilterManager::filterInputStringPost('name', 100);
        $object = FilterManager::filterInputStringPost('object');
        $content = FilterManager::filterInputStringPost('content', null);

        if (!FilterManager::isEmail($email)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.error.invalidMailFormat'),
            );

            Redirect::redirectPreviousRoute();
        }

        $encryptedMail = EncryptManager::encrypt($email);
        $encryptedName = EncryptManager::encrypt($name);
        $encryptedObject = EncryptManager::encrypt($object);
        $encryptedContent = EncryptManager::encrypt($content);

        $config = ContactSettingsModel::getInstance()->getConfig();
        if ($config === null || $config->getEmail() === null) {
            Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.error.notConfigured'));
            Redirect::redirectPreviousRoute();
        }

        if ($config->getAntiSpamActive()) {
            if (ContactController::getInstance()->isEmailBlacklisted($email)) {
                ContactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
            foreach ([$email, $name, $object, $content] as $input) {
                if (ContactController::getInstance()->containsBlacklistedWord($input)) {
                    ContactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                    Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                    Redirect::redirectPreviousRoute();
                }
            }
        }

        ContactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 0);

        MailManager::getInstance()->sendMail($email, $config->getObjectConfirmation(), $config->getMailConfirmation());
        MailManager::getInstance()->sendMail($config->getEmail(), '[' . Website::getWebsiteName() . ']' . LangManager::translate('contact.mail.object'), LangManager::translate('contact.mail.mail') . $email . LangManager::translate('contact.mail.name') . $name . LangManager::translate('contact.mail.object_sender') . $object . LangManager::translate('contact.mail.content') . $content);

        Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
            LangManager::translate('contact.toaster.send.success'));

        Redirect::redirectPreviousRoute();
    }

}
