<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

/* @var \CMW\Entity\Contact\ContactEntity[] $messages */

$title = LangManager::translate("contact.history.title");
$description = LangManager::translate("contact.history.description");

?>

<div class="page-title">
    <h3><i class="fa-solid fa-book-open"></i> <?= LangManager::translate("contact.history.title") ?></h3>
    <button type="submit" class="btn-danger btn-mass-delete loading-btn" data-loading-btn="Chargement" data-target-table="1">
        Supprimer la selection
    </button>
</div>

<div class="table-container">
    <table class="table-checkeable" data-form-action="history/deleteSelected" data-load-per-page="10" id="table1" >
        <thead>
        <tr>
            <th class="mass-selector"></th>
            <th><?= LangManager::translate("contact.message.name") ?></th>
            <th><?= LangManager::translate("contact.message.object") ?></th>
            <th><?= LangManager::translate("contact.message.state") ?></th>
            <th><?= LangManager::translate("contact.message.date") ?></th>
            <th class="text-center"><?= LangManager::translate("contact.message.read") ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message) : ?>
            <tr class="<?= $message->isRead() ? "h6" : '' ?>">
                <td class="item-selector" data-value="<?= $message->getId() ?>"></td>
                <td><?= mb_strimwidth($message->getName(), 0, 35, '...') ?></td>
                <td><?= mb_strimwidth($message->getObject(), 0, 50, '...') ?></td>
                <td>
                    <?php if ($message->isRead()):
                        echo LangManager::translate("contact.message.readed") . " - " . "<small>" . $message->getFirstReaderUser()?->getPseudo() . "</small>";
                    else :
                        echo LangManager::translate("contact.message.notread");
                    endif; ?>
                </td>
                <td><?= $message->getDate() ?></td>
                <td class="text-center space-x-2">
                    <a href="read/<?= $message->getId() ?>">
                        <i class="text-info fa fa-eye"></i>
                    </a>
                    <button data-modal-toggle="modal-<?= $message->getId() ?>" type="button"><i class="text-danger fas fa-trash-alt"></i></button>

                    <div id="modal-<?= $message->getId() ?>" class="modal-container">
                        <div class="modal">
                            <div class="modal-header-danger">
                                <h6><?= LangManager::translate("contact.message.deletetitle") ?></h6>
                                <button type="button" data-modal-hide="modal-<?= $message->getId() ?>"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <?= LangManager::translate("contact.message.deletealert") ?>
                            </div>
                            <div class="modal-footer">
                                <a href="../contact/delete/<?= $message->getId() ?>" class="btn-danger">
                                            <span
                                                class=""><?= LangManager::translate("contact.message.delete") ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>