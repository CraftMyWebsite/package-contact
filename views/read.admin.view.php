<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\Utils;
/* @var \CMW\Entity\Contact\ContactEntity $message */

$title = LangManager::translate("contact.read.title");
$description = LangManager::translate("contact.read.description"); ?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-book-open"></i> <span class="m-lg-auto"><?= LangManager::translate("contact.read.title") ?></span></h3>
</div>

<a href="<?= Utils::getHttpProtocol() . '://' . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . "cmw-admin/contact/history" ?>" class="btn btn-primary">
    <?= LangManager::translate("contact.read.back") ?>
</a>

<div class="row mt-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("contact.read.informations") ?></h4>
            </div>
            <div class="card-body">
                <p><?= LangManager::translate("contact.message.name") ?>: <b><?= $message->getName() ?></b></p>
                <p><?= LangManager::translate("contact.message.email") ?>: <b><?= $message->getEmail() ?></b></p>
                <p><?= LangManager::translate("contact.message.date") ?>: <b><?= $message->getDate() ?></b></p>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-success" onclick="print()">
                    <?= LangManager::translate("core.btn.download") ?>
                </button>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("contact.message.object") ?> : <?= $message->getObject() ?></h4>
            </div>
            <div class="card-body">
                <?= LangManager::translate("contact.read.message") ?>
                <div class="card-in-card p-2">
                    <?= $message->getContent() ?>
                </div>
                
            </div>
            <div class="card-footer text-end">
                <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-<?= $message->getId() ?>">
                    <?= LangManager::translate("contact.message.delete") ?>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="delete-<?= $message->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("contact.message.deletetitle") ?></h5>
            </div>
            <div class="modal-body">
                <?= LangManager::translate("contact.message.deletealert") ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                </button>
                <a href="../delete/<?= $message->getId() ?>" class="btn btn-danger">
                    <span class=""><?= LangManager::translate("contact.message.delete") ?></span>
                </a>
            </div>
        </div>
    </div>
</div>