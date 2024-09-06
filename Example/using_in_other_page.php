<!------------------------------------
    ----- Required namespace-----
-------------------------------------->
<?php
use CMW\Manager\Security\SecurityManager;
use CMW\Model\Contact\ContactModel;
?>

<!------------------------------------
        ----- CONTACT FORM -----
-------------------------------------->
<form action="" method="post">
    <?php (new SecurityManager())->insertHiddenToken() ?>
        <input type="email" name="email" required>
        <input type="text" name="name" required>
        <input type="text" name="object" required>
        <input type="text" name="content" required>
    <?php SecurityController::getPublicData(); ?> <!--!! CAPTCHA !!-->
    <button type="submit">Send</button>
</form>