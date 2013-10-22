<?php
// Kantia Version 0.3 index
require_once("../client_lib/client_config.php");
require_once("../lib/include.php");

$DIRECTORY_POSITION = "../";
$ACTION_FLAG = false

?>
<html>
<head>
<title>Chronicles System: Spells</title>
<?php
	include_once($DIRECTORY_POSITION.$CLIENT_TEMPLATE."metatags.php");
?>

</head>
<body>
<div id="PAGE">
<?php
require_once($DIRECTORY_POSITION.$CLIENT_TEMPLATE."header.php");
?>


<?php
require_once($DIRECTORY_POSITION.$CLIENT_TEMPLATE."sidebar.php");
?>

<div id="CONTENT">
<?php


if ( 	isset($_GET['disc_id']) && 
		$_GET['display'] == "detail" &&
		$ACTION_FLAG == false  ) {
	$discSVC = new DisciplineSVC;
	$spellSVC = new SpellSVC;

	$theDisc = $discSVC->get_by_id($_GET['disc_id']);
	$theSpells = $spellSVC->get_spells_by_discipline_id($theDisc->id);
	foreach ($theSpells as $sp) {
		$spellSVC->get_disciplines($sp);
		$spellSVC->get_spell_types($sp);	
	}


	echo SkillHTML::getSkillMenu();
	echo "<h1>".$theDisc->name." discipline</h1>";
//	echo SpellHTML::getJumpList($theSkills);
	echo "<p>".$theDisc->text."</p>";
	echo SpellHTML::getDetailMultiple($theSpells);
	$ACTION_FLAG = true;
}


if (	isset($_GET['spell_id']) &&
		$ACTION_FLAG == false ) {

	$spellSVC = new SpellSVC;
	$theSpell = $spellSVC->get_by_id($_GET['spell_id']);
	$spellSVC->get_disciplines($theSpell);
	$spellSVC->get_spell_types($theSpell);
	
	echo SpellHTML::getSpellMenu();
	echo "<h1>Spell Detail</h1>";
	echo SpellHTML::getDetail($theSpell);
	$ACTION_FLAG = true;
}



if ( 	$_GET['display'] == "list_all" &&
		$ACTION_FLAG == false) {
	
	$spellSVC = new SpellSVC;
	
	if ($_GET['disc_filter'] == "all") {
		$theSpells = $spellSVC->get_all_sum();
	} else if ( is_numeric($_GET['disc_filter']) ) {
		$theSpells = $spellSVC->get_spells_by_discipline_id($_GET['disc_filter']);
	} else {
		$theSpells = $spellSVC->get_all_sum();
	}

	echo SpellHTML::getSpellMenu();
	echo "<h1>Spells: Alphabetical Listing</h1>";
	echo SpellHTML::getDiscFilterForm($_GET['disc_filter']);
	echo "<p>Check up to 25 spells from the list below and click the submit button
			to view details for each spell that is selected.</p>\n";
	echo SpellHTML::getList($theSpells, 3, true);
	$ACTION_FLAG = true;
}



if (	isset($_GET['disc_id']) &&
		$_GET['display'] == "list" &&
		$ACTION_FLAG == false) {

	$spellSVC = new SpellSVC;
	$discSVC = new DisciplineSVC;
	$theDisc = $discSVC->get_by_id($_GET['disc_id']);

	$theSpells = $spellSVC->get_spells_by_discipline_id($theDisc->id);

	echo SpellHTML::getSpellMenu();
	echo "<h1>".$theDisc->name." discipline</h1>";
	echo "<p>".$theDisc->text."</p>";
	echo "<a href=\"spell_display.php?display=detail&disc_id=".$theDisc->id."\">Click here to see a full detail of all spells for this discipline.</a><hr />";

	foreach($theSpells as $spell) {
		echo "<a href=\"spell_display.php?display=detail&spell_id=".$spell->id."\">".$spell->name."</a><br />";
	}
	$ACTION_FLAG = true;
}


if (	isset($_GET['selected']) == 1 &&
		$ACTION_FLAG == false) {

	$spellSVC = new SpellSVC;
	$discSVC = new DisciplineSVC;
	$theSpells = $spellSVC->get_spells_by_id_list($_GET['sil'], 25);

	echo SpellHTML::getSpellMenu();
	echo "<h1>Selected Spell Detail</h1>";

	if (count($theSpells) < 1 ) {
		echo "<p>No records to display</p>";
	}
	foreach ($theSpells as $sp) {
		$spellSVC->get_disciplines($sp);
		$spellSVC->get_spell_types($sp);	
	}

	echo SpellHTML::getDetailMultiple($theSpells);

	$ACTION_FLAG = true;
}



if (	!isset($_GET['type_id']) &&
		!isset($_GET['genre_id']) &&
		!isset($_GET['camp_id']) &&
		!isset($_GET['skill_id']) &&
		$ACTION_FLAG == false) {

	echo SpellHTML::getSpellMenu();
	echo "<h1>Spell Disciplines</h1>\n";
	echo "<p>Click on a discipline below for a listing of the spells that are accessable within that discipline.</p>";
	$discSVC = new DisciplineSVC;
	$theDiscs = $discSVC->get_all();

	$lastType = "";
	foreach ($theDiscs as $disc) {
		if ($lastType != $disc->type) {
			echo "<h3>".$disc->type."</h3>\n";
		}
		echo "<div class=\"disc_listing\"><b><a href=\"spell_display.php?display=list&disc_id=".$disc->id."\">".$disc->name."</a></b>: ";
		echo $disc->text . "</div>\n";
		$lastType = $disc->type;
	}
	$ACTION_FLAG = true;
}
/****
 *	End default page rendering
 ****/
 
?>
</div>



<?php
require_once($DIRECTORY_POSITION.$CLIENT_TEMPLATE."footer.php");
?>


