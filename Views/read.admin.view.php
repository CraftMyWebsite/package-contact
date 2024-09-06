<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Utils\Website;

/* @var \CMW\Entity\Contact\ContactEntity $message */

$title = LangManager::translate('contact.read.title');
$description = LangManager::translate('contact.read.description');
?>

<div class="page-title">
    <h3><i class="fa-solid fa-book-open"></i> <?= LangManager::translate('contact.read.title') ?></h3>
    <a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'cmw-admin/contact/history' ?>"
       class="btn btn-primary">
        <?= LangManager::translate('contact.read.back') ?>
    </a>
</div>

<div class="grid-4">
    <div class="card">
        <h6><?= LangManager::translate('contact.read.informations') ?></h6>
        <p><?= LangManager::translate('contact.message.name') ?>: <b><?= $message->getName() ?></b></p>
        <p><?= LangManager::translate('contact.message.email') ?>: <b><?= $message->getEmail() ?></b></p>
        <p><?= LangManager::translate('contact.message.date') ?>: <b><?= $message->getDate() ?></b></p>
        <div class="space-x-2 text-center">
            <button class="btn btn-success" onclick="print()">
                <?= LangManager::translate('contact.button.download') ?>
            </button>
            <button data-modal-toggle="modal-danger" class="btn-danger"
                    type="button"><?= LangManager::translate('contact.message.delete') ?></button>
        </div>
    </div>
    <div class="col-span-3">
        <div class="card">
            <h6><?= LangManager::translate('contact.message.object') ?> : <?= $message->getObject() ?></h6>
            <?= LangManager::translate('contact.read.message') ?>
            <div class="card-in-card p-2">
                <?= $message->getContent() ?>
            </div>
        </div>
    </div>
</div>


<div id="modal-danger" class="modal-container">
    <div class="modal">
        <div class="modal-header-danger">
            <h6><?= LangManager::translate('contact.message.deletetitle') ?></h6>
            <button type="button" data-modal-hide="modal-danger"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <?= LangManager::translate('contact.message.deletealert') ?>
        </div>
        <div class="modal-footer">
            <a href="../delete/<?= $message->getId() ?>" class="btn-danger">
                <span class=""><?= LangManager::translate('contact.message.delete') ?></span>
            </a>
        </div>
    </div>
</div>