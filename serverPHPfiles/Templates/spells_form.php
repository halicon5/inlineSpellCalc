<?php
// all templates require an TDO object to defined and called before the template is called

$form = new FormTable("Spell", "exa_spells.php", true, true);
$form->hidden("table", "spells");
$form->hidden("function", $form_action);
$form->visible("Id", "spell_id", $theData->id);
$form->textfield("Name", "spell_name", $theData->name, 30, 60, true, $SpellTempNameVT);
$form->textfield("Keywords", "keywords", $theData->keywords, 60, 255);

$form->dropdownbox("Template", "temp_id", get_spell_template_options($theData->temp_id) );

$form->textbox("Pre-requisites", "prereq", $theData->prereq, 40, 2);
$form->textbox("Components", 	"components",	$theData->components, 40, 2);
$form->textfield("Minimum Cost", "min_cost",	$theData->min_cost, 	30, 60, true, $BasicSmallTextVT);
$form->double_text("Base Cost/TAV",	"base_cost", $theData->base_cost,
									"base_tav", $theData->base_tav, 30, 60, true, $BasicSmallTextVT);

$form->double_text("EPOT Cost/TAV", "epot_cost", $theData->epot_cost,
									"epot_tav", $theData->epot_tav, 30, 240, true, $BasicTextVT);

$form->double_text("AOE",	"aoe", $theData->aoe,
							"aoe_tav", $theData->aoe_tav, 30, 240, true, $BasicTextVT);

$form->double_text("Targets",	"targ", $theData->targ,
								"targ_tav", $theData->targ_tav, 30, 240, true, $BasicTextVT);
								
$form->double_text("Range",	"range", $theData->range,
							"range_tav", $theData->range_tav, 30, 240, true, $BasicTextVT);

$form->double_text("Duration",	"dur", $theData->dur,
								"dur_tav", $theData->dur_tav, 30, 240, true, $BasicTextVT);
								
$form->textbox("Karma", 		"karma",		$theData->karma,		60, 3);
$form->textfield("Surge",		"surge",		$theData->surge,		40, 240, true, $BasicTextVT);
$form->textfield("Drain",		"drain",		$theData->drain,		40, 240, true, $BasicTextVT);
$form->textfield("Damage",		"damage",		$theData->damage,		40, 240, true, $BasicTextVT);
$form->textfield("Staging",		"staging",		$theData->staging,		40, 240, true, $BasicTextVT);
$form->textfield("Resist",		"resist",		$theData->resist,		40, 240, true, $BasicTextVT);

$form->textbox("Description",		"description",		$theData->description,		60, 8);
$form->textbox("Effect",		"effect",		$theData->effect,		90, 8);
$form->textbox("Limits",		"limits",		$theData->limits,		90, 8);
$form->textbox("Special",		"special",		$theData->special,		90, 6);
$form->hidden("last_mod",	get_timestamp() );

$form->textbox("sCalc Variables",	"sCalcVars",	$theData->sCalcVars,	90, 8);
$form->textbox("sCalc Form Def",	"sCalcForm",	$theData->sCalcForm,	90, 8);
$form->textbox("sCalc Calculation",	"sCalcCalc",	$theData->sCalcCalc,	90, 8);
$form->textbox("sCalc Report Def",	"sCalcRep",		$theData->sCalcRep,		90, 8); 


$form->textbox("Notes",			"notes",		$theData->notes,		60, 2);

$form->is_active($theData->is_active);
$form->submit_button();

echo $form->print_table();

?>