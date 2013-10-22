<?php
// all templates require an TDO object to defined and called before the template is called

$form = new FormTable("Spell_Template", "exa_spell_templates.php", true, true);
$form->hidden("table", "spell_templates");
$form->hidden("function", $form_action);
$form->visible("Id", "temp_id", $theData->id);
$form->textfield("Template", "temp_name", $theData->name, 30, 60, true, $SpellTempNameVT);
$form->textfield("Keywords", "keywords", $theData->keywords, 60, 255);
$form->textbox("Pre-requisites", "tprereq", $theData->prereq, 40, 2);
$form->textbox("Components", 	"tcomponents",	$theData->components, 40, 2);
$form->textfield("Minimum Cost", "tmin_cost",	$theData->min_cost, 	30, 60, true, $BasicSmallTextVT);
$form->double_text("Base Cost/TAV",	"tbase_cost", $theData->base_cost,
									"tbase_tav", $theData->base_tav, 30, 60, true, $BasicSmallTextVT);

$form->double_text("EPOT Cost/TAV", "tepot_cost", $theData->epot_cost,
									"tepot_tav", $theData->epot_tav, 30, 240, true, $BasicTextVT);

$form->double_text("AOE",	"taoe", $theData->aoe,
							"taoe_tav", $theData->aoe_tav, 30, 240, true, $BasicTextVT);

$form->double_text("Targets",	"ttarg", $theData->targ,
								"ttarg_tav", $theData->targ_tav, 30, 240, true, $BasicTextVT);
								
$form->double_text("Range",	"trange", $theData->range,
							"trange_tav", $theData->range_tav, 30, 240, true, $BasicTextVT);

$form->double_text("Duration",	"tdur", $theData->dur,
								"tdur_tav", $theData->dur_tav, 30, 240, true, $BasicTextVT);
								
$form->textbox("Karma", 		"tkarma",		$theData->karma,		60, 4);
$form->textfield("Surge",		"tsurge",		$theData->surge,		40, 240, true, $BasicTextVT);
$form->textfield("Drain",		"tdrain",		$theData->drain,		40, 240, true, $BasicTextVT);
$form->textfield("Damage",		"tdamage",		$theData->damage,		40, 240, true, $BasicTextVT);
$form->textfield("Staging",		"tstaging",		$theData->staging,		40, 240, true, $BasicTextVT);
$form->textfield("Resist",		"tresist",		$theData->resist,		40, 240, true, $BasicTextVT);
$form->textbox("Effect",		"teffect",		$theData->effect,		90, 8);
$form->textbox("Limits",		"tlimits",		$theData->limits,		90, 4);
$form->textbox("Special",		"tspecial",		$theData->special,		90, 4);
$form->hidden("tlast_mod",	get_timestamp() );
$form->textbox("Notes",			"tnotes",		$theData->notes,		60, 4);

$form->is_active($theData->is_active);
$form->submit_button();

echo $form->print_table();

//$form->textfield("Base Cost", 	"tbase_cost",	$theData->base_cost,	30, 60, true, $BasicSmallTextVT);
//$form->textfield("Base TAV", 	"tbase_tav",	$theData->base_tav,		30, 240, true, $BasicTextVT);
//$form->textfield("EPOT Cost", 	"tepot_cost",	$theData->epot_cost,	30, 240, true, $BasicTextVT);
//$form->textfield("EPOT TAV", 	"tepot_tav",	$theData->epot_tav,		30, 240, true, $BasicTextVT);
//$form->textfield("AOE", 		"taoe",			$theData->aoe,			30, 240, true, $BasicTextVT);
//$form->textfield("AOE TAV", 	"taoe_tav",		$theData->aoe_tav,		30, 240, true, $BasicTextVT);
//$form->textfield("Targets", 	"ttarg",		$theData->targ,			30, 240, true, $BasicTextVT);
//$form->textfield("Targets TAV", "ttarg_tav",	$theData->targ_tav,		30, 240, true, $BasicTextVT);
//$form->textfield("Range", 		"trange",		$theData->range,		30, 240, true, $BasicTextVT);
//$form->textfield("Range TAV", 	"trange_tav",	$theData->range_tav,	30, 240, true, $BasicTextVT);
//$form->textfield("Duration", 	"tdur",			$theData->dur,			30, 240, true, $BasicTextVT);
//$form->textfield("Duration TAV","tdur_tav",		$theData->dur_tav,		30, 240, true, $BasicTextVT);


?>