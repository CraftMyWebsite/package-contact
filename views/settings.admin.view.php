<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

/* @var \CMW\Entity\Contact\ContactSettingsEntity $config */

$title = LangManager::translate("contact.settings.title");
$description = LangManager::translate("contact.settings.description");
?>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="" method="post" enctype="multipart/form-data">

                    <?php (new SecurityService())->insertHiddenToken() ?>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("contact.settings.title") ?> :</h3>
                        </div>
                        <div class="card-body">

                            <!-- CONFIG SECTION -->

                            <div class="form-group">
                                <label for="email"><?= LangManager::translate("contact.settings.email") ?></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" id="email" name="email" class="form-control"
                                           value="<?= $config->getEmail() ?>"
                                           placeholder="<?= LangManager::translate("users.users.mail") ?>">
                                </div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="captcha" name="captcha" class="form-check-input"
                                        <?= $config->captchaIsEnable() ? 'checked' : '' ?>>
                                <label for="captcha" class="form-check-label">
                                    <?= LangManager::translate("contact.settings.captcha_hint") ?>
                                </label>
                            </div>

                            <label for="summernote" class="mt-3">
                                <?= LangManager::translate("contact.settings.mail_confirmation") ?>
                            </label>
                            <div class="input-group mb-3">
                                <textarea id="summernote" name="mail" class="form-control" placeholder="" required>
                                    <?= $config->getMailConfirmation() ?>
                                </textarea>

                            </div>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary float-right"><?= LangManager::translate("core.btn.save", lineBreak: true) ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>
