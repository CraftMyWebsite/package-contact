<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

/* @var \CMW\Entity\Contact\ContactSettingsEntity $config */

$title = LangManager::translate('contact.settings.title');
$description = LangManager::translate('contact.settings.description');
?>

<div class="page-title">
    <h3><i class="fa-solid fa-gears"></i> <?= LangManager::translate('contact.settings.title') ?></h3>
    <button form="settings" type="submit"
            class="btn-primary"><?= LangManager::translate('core.btn.save', lineBreak: true) ?></button>
</div>

<div class="card">

    <form id="settings" action="" method="post">
        <?php SecurityManager::getInstance()->insertHiddenToken() ?>
        <div class="mb-4">
            <label class="toggle">
                <p class="toggle-label"><?= LangManager::translate('contact.antispam.setting') ?></p>
                <input type="checkbox" name="antiSpam" class="toggle-input" <?= $config->getAntiSpamActive() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <div class="grid-2">
            <div>
                <label for="email"><?= LangManager::translate('contact.settings.email') ?> :</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" required
                           placeholder="<?= LangManager::translate('users.users.mail') ?>" maxlength="255"
                           value="<?= $config->getEmail() ?>">
                </div>
                <h6></h6>
            </div>
            <div>
                <label for="object"><?= LangManager::translate('contact.settings.object_confirmation') ?> :</label>
                <div class="input-group">
                    <i class="fas fa-object-group"></i>
                    <input type="text" id="object" name="object" required
                           value="<?= $config->getObjectConfirmation() ?>"
                           placeholder="<?= LangManager::translate('contact.settings.object_confirmation_placeholder') ?>"
                           maxlength="255">
                </div>
            </div>
        </div>
        <label for="mail"><?= LangManager::translate('contact.settings.mail_confirmation') ?> :</label>
        <textarea name="mail" id="mail" class="tinymce"><?= $config->getMailConfirmation() ?></textarea>
    </form>
</div>
