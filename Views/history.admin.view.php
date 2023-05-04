<?php

use CMW\Manager\Lang\LangManager;

$title = LangManager::translate("contact.history.title");
$description = LangManager::translate("contact.history.description");

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-book-open"></i> <span class="m-lg-auto"><?= LangManager::translate("contact.history.title") ?></span></h3>
</div>

<div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
        <table class="table" id="table1">
            <thead>
            <tr>
                <th class="text-center"><?= LangManager::translate("contact.message.name") ?></th>
                <th class="text-center"><?= LangManager::translate("contact.message.object") ?></th>
                <th class="text-center"><?= LangManager::translate("contact.message.state") ?></th>
                <th class="text-center"><?= LangManager::translate("contact.message.date") ?></th>
                <th class="text-center"><?= LangManager::translate("contact.message.read") ?></th>
            </tr>
            </thead>
            <tbody class="text-center">
                <?php /* @var \CMW\Entity\Contact\ContactEntity[] $messages */
                foreach ($messages as $message) : ?>
                <tr class="<?php if ($message->isRead()!=1) { echo "h6";} ?>">
                    <td><?= mb_strimwidth($message->getName(), 0, 35, '...') ?></td>
                    <td><?= mb_strimwidth($message->getObject(), 0, 50, '...') ?></td>
                    <td> 
                        <?php if ($message->isRead()==1) {
                            echo LangManager::translate("contact.message.readed");
                        } else {
                            echo LangManager::translate("contact.message.notread");
                        } ?>
                    </td>
                    <td><?= $message->getDate() ?></td>
                    <td class="text-center">
                        <a href="read/<?= $message->getId() ?>">
                            <i class="text-primary me-3 fa fa-eye"></i>
                        </a>
                        <a type="button" data-bs-toggle="modal" data-bs-target="#delete-<?= $message->getId() ?>">
                            <i class="text-danger fas fa-trash-alt"></i>
                        </a>
                        <div class="modal fade text-left" id="delete-<?= $message->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                        <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("contact.message.deletetitle") ?></h5>
                                    </div>
                                    <div class="modal-body text-left">
                                        <?= LangManager::translate("contact.message.deletealert") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <a href="../contact/delete/<?= $message->getId() ?>" class="btn btn-danger">
                                            <span class=""><?= LangManager::translate("contact.message.delete") ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>