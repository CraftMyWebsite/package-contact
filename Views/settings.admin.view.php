<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

/* @var \CMW\Entity\Contact\ContactSettingsEntity $config */

$title = LangManager::translate("contact.settings.title");
$description = LangManager::translate("contact.settings.description");
?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span class="m-lg-auto"><?= LangManager::translate("contact.settings.title") ?></span></h3>
</div>

<section>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form action="" method="post">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("contact.settings.email") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input class="form-control" type="email" id="email" name="email" required placeholder="<?= LangManager::translate("users.users.mail") ?>" maxlength="255" value="<?= $config->getEmail() ?>">
                                <div class="form-control-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("contact.settings.object_confirmation") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input class="form-control" type="text" id="object" name="object" required value="<?= $config->getObjectConfirmation() ?>" placeholder="<?= LangManager::translate("contact.settings.object_confirmation_placeholder") ?>" maxlength="255">
                                <div class="form-control-icon">
                                    <i class="fas fa-object-group"></i>
                                </div>
                            </div>
                        </div>     
                    </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="captcha" name="captcha" <?= $config->captchaIsEnable() ? 'checked' : '' ?>>
                    <label class="form-check-label" for="captcha"><?= LangManager::translate("contact.settings.captcha_hint") ?></label>
                </div>
                <h6 class="mt-2"><?= LangManager::translate("contact.settings.mail_confirmation") ?> :</h6>
                <textarea name="mail" class="tinymce"><?= $config->getMailConfirmation() ?></textarea>
                <div class="text-center mt-2">
                    <button type="submit" class="btn btn-primary"><?= LangManager::translate("core.btn.save", lineBreak: true) ?></button>
                </div>
            </form>
        </div>
    </div>
</section>