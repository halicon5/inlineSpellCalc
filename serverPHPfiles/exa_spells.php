<?php
require_once("../lib/include.php");
check_security();
$theService = new SpellSVC;

verify_magic_quotes();

$id = add_or_edit_from_post($theService);

$form_action = $_POST['function'];
$tablename = $_POST['table'];
$view_url="view_record.php?id=".$id."&table=".$tablename;
$select_url="sel_".$tablename.".php";

?>



<?php
require_once("templates/tmp_exa_body.php");
?>