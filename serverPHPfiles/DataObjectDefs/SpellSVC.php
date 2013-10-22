<?php
class SpellSVC {
	private $fSpell;

	public function __construct() {
		$this->fSpell = new SpellDAO_mysql;
	}
	
	public function get_new_record_from_post() {
		return $this->get_new_spell_from_post();
	}

	public function get_edit_record_from_post() {
		return $this->get_edit_spell_from_post();
	}
	





	public function add_record(SpellTDO $aData) {
		$this->add_spell($aData);
	}
	
	public function update_record(SpellTDO $aData) {
		$this->update_spell($aData);
	}
	
	public function delete_record($aId) {
		$this->remove_spell($aId);
	}



	

	public function get_all($aActive = true, $aDate = "0000-00-00") {
		return $this->fSpell->get_all_spells($aActive, $aDate);
	}
	
	public function get_all_sum($aActive = true, $aDate = "0000-00-00") {
		return $this->fSpell->get_all_spells_sum($aActive, $aDate);
	}

	public function get_spells_by_id_list($aList, $limit = -1) {
		return $this->fSpell->get_spells_by_id_list($aList, $limit);
	}



	public function get_new_spell_from_post() {
		$theItem = new SpellTDO;
		$theItem->name = $_POST['spell_name'];
		$theItem->temp_id = $_POST['temp_id'];
		
		$theItem->prereq = $_POST['prereq'];
		$theItem->components = $_POST['components'];
		
		$theItem->min_cost = $_POST['min_cost'];
		$theItem->base_cost = $_POST['base_cost'];
		$theItem->base_tav = $_POST['base_tav'];
		$theItem->epot_cost = $_POST['epot_cost'];
		$theItem->epot_tav = $_POST['epot_tav'];
		$theItem->aoe = $_POST['aoe'];
		$theItem->aoe_tav = $_POST['aoe_tav'];
		$theItem->targ = $_POST['targ'];
		$theItem->targ_tav = $_POST['targ_tav'];
		$theItem->range = $_POST['range'];
		$theItem->range_tav = $_POST['range_tav'];
		$theItem->dur = $_POST['dur'];
		$theItem->dur_tav = $_POST['dur_tav'];
		$theItem->karma = $_POST['karma'];
		
		$theItem->surge = $_POST['surge'];
		$theItem->drain = $_POST['drain'];
		
		$theItem->damage = $_POST['damage'];
		$theItem->staging = $_POST['staging'];
		$theItem->resist = $_POST['resist'];
		
		$theItem->description = $_POST['description'];
		$theItem->effect = $_POST['effect'];
		$theItem->limits = $_POST['limits'];
		$theItem->special = $_POST['special'];
		
		$theItem->last_mod = $_POST['last_mod'];
		$theItem->keywords = $_POST['keywords'];
		$theItem->notes = $_POST['notes'];
		$theItem->is_active = $_POST['is_active'];

		$theItem->sCalcVars = $_POST['sCalcVars'];
		$theItem->sCalcForm = $_POST['sCalcForm'];
		$theItem->sCalcCalc = $_POST['sCalcCalc'];
		$theItem->sCalcRep = $_POST['sCalcRep'];

		

		return $theItem;
	}
	


	public function get_edit_spell_from_post() {
		$theItem = $this->get_new_spell_from_post();
		$theItem->id = $_POST['spell_id'];
		return $theItem;
	}
	
	private function get_cross_refs_from_post() {
		$cross_refs['genres'] = $this->get_cross_ids_from_post("genre_ids");
		$cross_refs['disciplines'] = $this->get_cross_ids_from_post("discipline_ids");
		$cross_refs['spell_types'] = $this->get_cross_ids_from_post("spell_type_ids");
		$cross_refs['campaigns'] = $this->get_cross_ids_from_post("campaign_ids");
		return $cross_refs;
	}

	private function get_cross_ids_from_post($field_name) {
		$ids = array();
		
		if (is_array($_POST[$field_name]) ) {
			$ids = $_POST[$field_name];
		}
		
		return $ids;
	}
	
	
	

	public function update_cross_references($spell_id) {
		$theSpell = $this->get_by_id($spell_id);
		$cross_refs = $this->get_cross_refs_from_post();
		$this->fSpell->update_all_cross_references($theSpell, $cross_refs);
	}



	
	public function add_spell(SpellTDO $aData) {
		$this->fSpell->add_spell($aData);
	}
	
	public function update_spell(SpellTDO $aData) {
		$this->fSpell->update_spell($aData);
	}
	
	public function remove_spell($aId) {
		$this->fSpell->remove_spell($aId);
	}
	
	public function get_by_id($aId) {
		return $this->fSpell->get_spell_by_id($aId);
	}
	
	public function get_by_name($aName) {
		return $this->fSpell->get_spell_by_name($aName);
	}
	
	public function get_spell_sums_by_discipline_id($aId) {
		return $this->fSpell->get_spell_sums_by_discipline_id($aId);
	}
	
	public function get_spells_by_discipline_id($aId) {
		return $this->fSpell->get_spells_by_discipline_id($aId);
	}
	
	


	// cross ref functions
	public function get_all_cross_refs(SpellTDO $aSpell) {
		$this->get_spell_types($aSpell);
//		$this->get_spell_includes($aSpell);
		$this->get_genres($aSpell);
		$this->get_campaigns($aSpell);
		$this->get_disciplines($aSpell);
	}
	
	public function get_spell_types(SpellTDO $aSpell) {
		$this->fSpell->get_spell_types($aSpell);
	}
	
	public function get_spell_includes(SpellTDO $aSpell) {
		$this->fSpell->get_spell_includes($aSpell);
	}
	
	public function get_genres(SpellTDO $aSpell) {
		$this->fSpell->get_genres($aSpell);
	}
	
	public function get_campaigns(SpellTDO $aSpell) {
		$this->fSpell->get_campaigns($aSpell);
	}
	
	public function get_disciplines(SpellTDO $aSpell) {
		$this->fSpell->get_disciplines($aSpell);
	}	
}

?>