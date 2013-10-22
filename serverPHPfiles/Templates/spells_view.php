<?php
// all view templates require an TDO object to befined and called before the template is called

$theService = new SpellSVC;
$theData = $theService->get_by_id($id);


// start the table
$theRecord = new Table($ViewTableOPTs);
$rows = array();

$rows[] = new_view_tr("Id:", $theData->id);
$rows[] = new_view_tr("Spell Name:", $theData->name);
$rows[] = new_view_tr("Pre-requisites:", $theData->prereq);
$rows[] = new_view_tr("Components:", $theData->components);
$rows[] = new_view_tr("Minimum Cost:", $theData->min_cost);
$rows[] = new_view_tr("", "(Strain)", "(TAV)");
$rows[] = new_view_tr("Base", $theData->base_cost, $theData->base_tav);
$rows[] = new_view_tr("EPOT", $theData->epot_cost, $theData->epot_tav);
$rows[] = new_view_tr("AOE", $theData->aoe, $theData->aoe_tav);
$rows[] = new_view_tr("Targets", $theData->targ, $theData->targ_tav);
$rows[] = new_view_tr("Range", $theData->range, $theData->range_tav);
$rows[] = new_view_tr("Duration", $theData->dur, $theData->dur_tav);
$rows[] = new_view_tr("Karma", $theData->karma);
$rows[] = new_view_tr("Drain", $theData->drain);
$rows[] = new_view_tr("Surge", $theData->surge);
$rows[] = new_view_tr("Damage", $theData->damage);
$rows[] = new_view_tr("Staging", $theData->staging);
$rows[] = new_view_tr("Resist", $theData->resist);
$rows[] = new_view_tr("Description", $theData->description);
$rows[] = new_view_tr("Effect", $theData->effect);
$rows[] = new_view_tr("Limits", $theData->limits);
$rows[] = new_view_tr("Special", $theData->special);
$rows[] = new_view_tr("Last Mod:", $theData->last_mod);
$rows[] = new_view_tr("Notes", $theData->notes);
$rows[] = new_view_tr("Active", $theData->is_active);


$theRecord->add_rows($rows);
?>