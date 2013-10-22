<?php

class SpellTDO {
	public $id;
	public $name;
	public $temp_id;
	
	public $prereq;
	public $components;
	
	public $min_cost;
	public $base_cost;
	public $base_tav;
	public $epot_cost;
	public $epot_tav;
	public $aoe;
	public $aoe_tav;
	public $targ;
	public $targ_tav;
	public $range;
	public $range_tav;
	public $dur;
	public $dur_tav;
	public $karma;
	
	public $surge;
	public $drain;
	
	public $damage;
	public $staging;
	public $resist;
	
	public $description;
	public $effect;
	public $limits;
	public $special;

	public $sCalcVars;
	public $sCalcForm;
	public $sCalcCalc;
	public $sCalcRep;


	public $last_mod;
	public $notes;
	public $is_active;	

	public $disciplines;	//	array of discipline TDOs
	public $spell_types;	//	array of spell_type TDOs
	public $genres;			//	
	public $campaigns;		//	

}

?>
