<?php
class SpellTemplateSVC {
	private $fSTemp;

	public function __construct() {
		$this->fSTemp = new SpellTemplateDAO_mysql;
	}
	
	public function get_new_record_from_post() {
		return $this->get_new_stemp_from_post();
	}

	public function get_edit_record_from_post() {
		return $this->get_edit_stemp_from_post();
	}
	
	public function add_record(SpellTemplateTDO $aData) {
		$this->add_stemp($aData);
	}
	
	public function update_record(SpellTemplateTDO $aData) {
		$this->update_stemp($aData);
	}
	
	public function delete_record($aId) {
		$this->remove_stemp($aId);
	}
	
	public function get_new_stemp_from_post() {
		$theItem = new SpellTemplateTDO;
		$theItem->name = $_POST['temp_name'];
		$theItem->prereq = $_POST['tprereq'];
		$theItem->components = $_POST['tcomponents'];
		$theItem->min_cost = $_POST['tmin_cost'];
		$theItem->base_cost = $_POST['tbase_cost'];
		$theItem->base_tav = $_POST['tbase_tav'];
		$theItem->epot_cost = $_POST['tepot_cost'];
		$theItem->epot_tav = $_POST['tepot_tav'];
		$theItem->aoe = $_POST['taoe'];
		$theItem->aoe_tav = $_POST['taoe_tav'];
		$theItem->targ = $_POST['ttarg'];
		$theItem->targ_tav = $_POST['ttarg_tav'];
		$theItem->range = $_POST['trange'];
		$theItem->range_tav = $_POST['trange_tav'];
		$theItem->dur = $_POST['tdur'];
		$theItem->dur_tav = $_POST['tdur_tav'];
		$theItem->karma = $_POST['tkarma'];
		$theItem->surge = $_POST['tsurge'];
		$theItem->drain = $_POST['tdrain'];
		$theItem->damage = $_POST['tdamage'];
		$theItem->staging = $_POST['tstaging'];
		$theItem->resist = $_POST['tresist'];
		$theItem->effect = $_POST['teffect'];
		$theItem->limits = $_POST['tlimits'];
		$theItem->special = $_POST['tspecial'];
		$theItem->notes = $_POST['tnotes'];
		$theItem->last_mod = $_POST['tlast_mod'];
		$theItem->keywords = $_POST['keywords'];
		$theItem->is_active = $_POST['is_active'];
		return $theItem;
	}
	
	public function get_edit_stemp_from_post() {
		$theItem = $this->get_new_stemp_from_post();
		$theItem->id = $_POST['temp_id'];
		return $theItem;
	}
	
	public function add_stemp(SpellTemplateTDO $aData) {
		$this->fSTemp->add_spell_temp($aData);
	}
	
	public function update_stemp(SpellTemplateTDO $aData) {
		$this->fSTemp->update_spell_temp($aData);
	}
	
	public function remove_stemp($aId) {
		$this->fSTemp->remove_spell_temp($aId);
	}
	
	public function get_by_id($aId) {
		return $this->fSTemp->get_spell_temp_by_id($aId);
	}
	
	public function get_all($aActive = true) {
		return $this->fSTemp->get_all_spell_temps($aActive);
	}
	
	public function get_all_sum($aActive = true) {
		return $this->fSTemp->get_all_spell_temps_sum($aActive);
	}
}

?>