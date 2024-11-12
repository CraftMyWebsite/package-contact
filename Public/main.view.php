<?php

use CMW\Controller\Core\SecurityController;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

Website::setTitle('Contactez-nous');
Website::setDescription('Contactez-nous dès maintenant');
?>
<?php if (\CMW\Controller\Users\UsersController::isAdminLogged()): ?>
    <div style="background-color: orange; padding: 6px; margin-bottom: 10px">
        <span>Votre thème ne gère pas cette page !</span>
        <br>
        <small>Seuls les administrateurs voient ce message !</small>
    </div>
<?php endif;?>

<form style="width: 100%;" method="post">
    <?php SecurityManager::getInstance()->insertHiddenToken() ?>
    <div>
        <label for="email" style="display: block">Mail</label>
        <input name="email" id="email" type="email" style="width: 100%" placeholder="mail@craftmywebsite.fr" required>
    </div>
    <div style="margin-top: 1rem">
        <label for="name" style="display: block">Nom</label>
        <input name="name" id="name" type="text" style="width: 100%" placeholder="Jean" required>
    </div>
    <div style="margin-top: 1rem">
        <label for="object" style="display: block">Objet</label>
        <input name="object" id="object" type="text" style="width: 100%" placeholder="J'aimerais aborder" required>
    </div>
    <div style="margin-top: 1rem">
        <label for="content" style="display: block">Mail</label>
        <textarea name="content" id="content" minlength="50" style="width: 100%"></textarea>
    </div>
    <?php SecurityController::getPublicData(); ?>
    <button type="submit" style="display: flex; justify-self: center; margin-top: 1rem;">Soumettre</button>
</form>