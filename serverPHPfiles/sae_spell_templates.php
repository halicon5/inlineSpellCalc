<?php
require_once("../lib/include.php");
check_security();

$table_name = "spell_templates";
$page_title	= "Add/Update Spell Template";
$theData = new SpellTemplateTDO;

$theService = new SpellTemplateSVC;
if ($_GET['id'] > 0) {
	$theData = $theService->get_by_id($_GET['id']);
	$form_action = "edit";
} else {
	$form_action = "add";
	$theData->is_active = "Y";
}

$select_url = "sel_".$table_name.".php";
require_once("templates/tmp_sae_header.php");
require_once("templates/".$table_name."_form.php");
require_once("templates/tmp_sae_footer.php");
?>


</body>
</html>