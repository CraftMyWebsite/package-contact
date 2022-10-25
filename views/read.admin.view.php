<?php

use CMW\Manager\Lang\LangManager;

/* @var \CMW\Entity\Contact\ContactEntity $message */

$title = LangManager::translate("contact.read.title");
$description = LangManager::translate("contact.read.description"); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-between" id="content">

            <div class="card col-8">
                <div class="card-header">
                    <h3 class="text-center"><?= $message->getObject() ?></h3>
                </div>
                <div class="card-body">
                    <p>
                        <?= $message->getContent() ?>
                    </p>
                </div>

            </div>


            <!-- SIDEBAR RIGHT -->
            <div class="card col-3 ">
                <div class="card-header">
                    <h5 class="text-center"><?= LangManager::translate("contact.read.informations") ?></h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><?= LangManager::translate("contact.message.name") ?>:
                            <b><?= $message->getName() ?></b>
                        </li>
                        <li><?= LangManager::translate("contact.message.email") ?>:
                            <b><?= $message->getEmail() ?></b>
                        </li>
                        <li><?= LangManager::translate("contact.message.date") ?>:
                            <b><?= $message->getDate() ?></b>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-success" onclick="print()">
                        <?= LangManager::translate("core.btn.download") ?>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>