<?php

namespace CMW\Controller\Contact\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Controller\Users\UsersSessionsController;
use CMW\Manager\Filter\FilterManager;
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
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @ContactAdminController
 * @package Contact
 */
class ContactAdminController extends AbstractController
{
    #[Link(path: '/', method: Link::GET, scope: '/cmw-admin/contact')]
    #[Link('/settings', Link::GET, [], '/cmw-admin/contact')]
    private function adminContactSettings(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.settings');

        $config = contactSettingsModel::getInstance()->getConfig();

        View::createAdminView('Contact', 'settings')
            ->addScriptBefore('Admin/Resources/Vendors/Tinymce/tinymce.min.js',
                'Admin/Resources/Vendors/Tinymce/Config/full_absolute_links.js')
            ->addVariableList(['config' => $config])
            ->view();
    }

    #[NoReturn] #[Link('/settings', Link::POST, [], '/cmw-admin/contact')]
    private function adminContactSettingsPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.settings');

        [$email, $object, $mail, $antiSpam] = Utils::filterInput('email', 'object', 'mail', 'antiSpam');

        if (is_null($antiSpam)) {
            $antiSpam = 0;
        }

        contactSettingsModel::getInstance()->updateConfig($email ?? null, $antiSpam, $object, $mail);

        Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
            LangManager::translate('core.toaster.config.success'));

        Redirect::redirectPreviousRoute();
    }

    #[Link('/history', Link::GET, [], '/cmw-admin/contact')]
    private function adminContactHistory(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.history');

        $messages = ContactModel::getInstance()->getMessages();
        $unreadNonSpam = ContactModel::getInstance()->countUnreadNonSpam();
        $unreadSpam = ContactModel::getInstance()->countUnreadSpam();

        View::createAdminView('Contact', 'history')
            ->addStyle('Admin/Resources/Assets/Css/simple-datatables.css')
            ->addScriptAfter('Admin/Resources/Vendors/Simple-datatables/simple-datatables.js',
                'Admin/Resources/Vendors/Simple-datatables/config-datatables.js')
            ->addVariableList(['messages' => $messages, 'unreadNonSpam' => $unreadNonSpam, 'unreadSpam' => $unreadSpam])
            ->view();
    }

    #[Link('/history/spam', Link::GET, [], '/cmw-admin/contact')]
    private function adminContactHistorySpam(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.history');

        $messages = ContactModel::getInstance()->getMessages();
        $unreadNonSpam = ContactModel::getInstance()->countUnreadNonSpam();
        $unreadSpam = ContactModel::getInstance()->countUnreadSpam();

        View::createAdminView('Contact', 'historySpam')
            ->addStyle('Admin/Resources/Assets/Css/simple-datatables.css')
            ->addScriptAfter('Admin/Resources/Vendors/Simple-datatables/simple-datatables.js',
                'Admin/Resources/Vendors/Simple-datatables/config-datatables.js')
            ->addVariableList(['messages' => $messages, 'unreadNonSpam' => $unreadNonSpam, 'unreadSpam' => $unreadSpam])
            ->view();
    }

    //Secure false because we use this method in history spam with data-form-action
    #[NoReturn] #[Link('/history/deleteSelected', Link::POST, [], '/cmw-admin/contact', secure: false)]
    private function adminDeleteSelectedPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.settings');

        $messageIds = $_POST['selectedIds'];

        if (empty($messageIds)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.error.noMessageSelected'),
            );
        }

        $i = 0;
        foreach ($messageIds as $messageId) {
            $messageId = FilterManager::filterData($messageId, 11, FILTER_SANITIZE_NUMBER_INT);
            if (!ContactModel::getInstance()->deleteMessage($messageId)) {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate('core.toaster.error'),
                    LangManager::translate('contact.toaster.error.deletingMessage', ['id' => $messageId]),
                );
            }
            $i++;
        }

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('contact.toaster.success.deleteMessages', ['number' => $i]),
        );

        Redirect::redirectPreviousRoute();
    }

    #[Link('/read/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/contact')]
    private function adminContactRead(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.history');

        $message = ContactModel::getInstance()->getMessageById($id);
        $userId = UsersSessionsController::getInstance()->getCurrentUser()?->getId();

        if (!is_null($userId) && !$message?->isRead()) {
            ContactModel::getInstance()->setMessageState($id, $userId);
        }

        View::createAdminView('Contact', 'read')
            ->addScriptAfter('App/Package/Contact/Views/Resources/Js/print.js')
            ->addVariableList(['message' => $message])
            ->view();
    }

    #[NoReturn]
    #[Link('/delete/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/contact')]
    private function adminContactDelete(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.delete');

        if (ContactModel::getInstance()->deleteMessage($id)) {
            Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
                LangManager::translate('contact.toaster.delete.success'));
        } else {
            Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
                LangManager::translate('contact.toaster.delete.error'));
        }

        Redirect::redirectToAdmin("contact/history");
    }

    #[Link('/stats', Link::GET, [], '/cmw-admin/contact')]
    private function adminContactStats(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.stats');

        View::createAdminView('Contact', 'stats')
            ->view();
    }

    #[NoReturn] #[Link('/spam/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/contact')]
    private function adminContactSpam(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.history');

        ContactModel::getInstance()->setSpam($id, 1);

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('contact.antispam.title'),
            LangManager::translate('contact.toaster.success.markAsSpam'),
        );

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/nonSpam/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/contact')]
    private function adminContactNonSpam(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'contact.history');

        ContactModel::getInstance()->setSpam($id, 0);

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('contact.antispam.title'),
            LangManager::translate('contact.toaster.success.markAsNotSpam'),
        );

        Redirect::redirectPreviousRoute();
    }
}
