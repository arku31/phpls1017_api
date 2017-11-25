<?php
require_once "facebook_class.php";

$fb = new arkufb();
?>
<a href="<?=$fb->getButtonUrl();?>">Войти через фейсбук</a>

<?php
if (isset($_GET['code'])) {
//    $fb->doLogin();
    $fb->doPostSmth();
}