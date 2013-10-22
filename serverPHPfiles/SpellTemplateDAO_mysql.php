<?php
/**
 *	class GenreDAO_mysql
 *	Transfer Domain Object for Genres.
 */
class SpellTemplateDAO_mysql implements ISpellTemplateDAO {

	public function get_all_spell_temps($aActive = true) {
		$sql = "SELECT *
				FROM spell_templates\n";
		
		if ($aActive == true) {
			$sql .= "WHERE is_active = 'Y'\n";
		}

		$sql .= "ORDER BY temp_name";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theTemplates = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row)
				$theTemplates[] = $this->populate($row);
			return $theTemplates;
		} else {
			return false;
		}
	}

	/**
	 *	same as get_all_spell_temps except it only gets a summary of data
	 */
	public function get_all_spell_temps_sum($aActive = true) {
		$sql = "SELECT temp_id, temp_name, tprereq, tnotes, is_active
				FROM spell_templates\n";

		if ($aActive == true) {
			$sql .= "WHERE is_active = 'Y'\n";
		}
		
		$sql .= "ORDER BY temp_name";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theTemplates = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row)
				$theTemplates[] = $this->populate_summary($row);
			return $theTemplates;
		} else {
			return false;
		}
	}
	

	/**
	 *	get_spell_temp_by_id()
	 *	@param	int		id
	 */
	public function get_spell_temp_by_id($aId) {
		$sql = "SELECT *
				FROM spell_templates
				WHERE temp_id = '$aId'";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		return (count($theData) == 1) ? $this->populate($theData[0]) : new SpellTemplateTDO;
	}

	/**
	 *	get_spell_temp_by_name()
	 *	@param	string
	 */
	public function get_spell_temp_by_name($aName) {
		$sql = "SELECT *
				FROM spell_templates
				WHERE temp_name = '$aName'";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		return (count($theData) == 1) ? $this->populate($theData[0]) : false;
	}
	

	/**
	 *	add_spell_template()
	 *	@param	GenreDAO
	 */
	public function add_spell_temp(SpellTemplateTDO $aData) {
		$sql = "INSERT INTO spell_templates
				(	temp_id,
					temp_name,
					tprereq,
					tcomponents,
					tmin_cost,
					tbase_cost,
					tbase_tav,
					tepot_cost,
					tepot_tav,
					taoe,
					taoe_tav,
					ttarg,
					ttarg_tav,
					trange,
					trange_tav,
					tdur,
					tdur_tav,
					tkarma,
					tsurge,
					tdrain,
					tdamage,
					tstaging,
					tresist,
					teffect,
					tlimits,
					tspecial,
					tlast_mod,
					tnotes,
					keywords,
					is_active	)
				VALUES 
				(	'',
					'$aData->name',
					'$aData->prereq',
					'$aData->components',
					'$aData->min_cost',
					'$aData->base_cost',
					'$aData->base_tav',
					'$aData->epot_cost',
					'$aData->epot_tav',
					'$aData->aoe',
					'$aData->aoe_tav',
					'$aData->targ',
					'$aData->targ_tav',
					'$aData->range',
					'$aData->range_tav',
					'$aData->dur',
					'$aData->dur_tav',
					'$aData->karma',
					'$aData->surge',
					'$aData->drain',
					'$aData->damage',
					'$aData->staging',
					'$aData->resist',
					'$aData->effect',
					'$aData->limits',
					'$aData->special',
					'$aData->last_mod',
					'$aData->notes',
					'$aData->keywords',
					'$aData->is_active' )";
		$result = DBcon::Run_Query($sql);
		$aData->id = mysql_insert_id();
	}

	
	public function update_spell_temp(SpellTemplateTDO $aData) {
		$sql = "UPDATE spell_templates
				SET	temp_name = '$aData->name',
					tprereq = '$aData->prereq',
					tcomponents = '$aData->components',
					tmin_cost = '$aData->min_cost',
					tbase_cost = '$aData->base_cost',
					tbase_tav = '$aData->base_tav',
					tepot_cost = '$aData->epot_cost',
					tepot_tav = '$aData->epot_tav',
					taoe = '$aData->aoe',
					taoe_tav = '$aData->aoe_tav',
					ttarg = '$aData->targ',
					ttarg_tav = '$aData->targ_tav',
					trange = '$aData->range',
					trange_tav = '$aData->range_tav',
					tdur = '$aData->dur',
					tdur_tav = '$aData->dur_tav',
					tkarma = '$aData->karma',
					tsurge = '$aData->surge',
					tdrain = '$aData->drain',
					tdamage = '$aData->damage',
					tstaging = '$aData->staging',
					tresist = '$aData->resist',
					teffect = '$aData->effect',
					tlimits = '$aData->limits',
					tspecial = '$aData->special',
					tlast_mod= '".get_timestamp()."',
					tnotes = '$aData->notes',
					keywords = '".$aData->keywords."',
					is_active = '$aData->is_active'
				WHERE temp_id = '$aData->id'";
				
		$result = DBcon::Run_Query($sql);
		
		$sql = "UPDATE spells
					SET last_mod ='".get_timestamp()."'
				WHERE temp_id = '$aData->id'";
		
		$result = DBcon::Run_Query($sql);
	}

	public function remove_spell_temp($aId) {
		$sql = "DELETE FROM spell_templates
				WHERE temp_id = '$aId'";
				
		$result = DBcon::Run_Query($sql);
	}
	
	/**
	 *	populate()
	 *	@param	array
	 *	@return	SpellTemplateTDO
	 */
	public function populate($aData) {
		$theTemp = new SpellTemplateTDO;
		
		$theTemp->id	=	$aData['temp_id'];
		$theTemp->name	=	$aData['temp_name'];
		$theTemp->prereq =	$aData['tprereq'];		
		$theTemp->components = $aData['tcomponents'];
		$theTemp->min_cost = $aData['tmin_cost'];
		$theTemp->base_cost = $aData['tbase_cost'];
		$theTemp->base_tav = $aData['tbase_tav'];
		$theTemp->epot_cost = $aData['tepot_cost'];
		$theTemp->epot_tav = $aData['tepot_tav'];
		$theTemp->aoe	= $aData['taoe'];
		$theTemp->aoe_tav = $aData['taoe_tav'];
		$theTemp->targ	= $aData['ttarg'];
		$theTemp->targ_tav = $aData['ttarg_tav'];
		$theTemp->range	= $aData['trange'];
		$theTemp->range_tav = $aData['trange_tav'];
		$theTemp->dur	= $aData['tdur'];
		$theTemp->dur_tav = $aData['tdur_tav'];
		$theTemp->karma = $aData['tkarma'];
		$theTemp->surge = $aData['tsurge'];
		$theTemp->drain = $aData['tdrain'];
		$theTemp->damage = $aData['tdamage'];
		$theTemp->staging = $aData['tstaging'];
		$theTemp->resist = $aData['tresist'];
		$theTemp->effect = $aData['teffect'];
		$theTemp->limits = $aData['tlimits'];
		$theTemp->special = $aData['tspecial'];
		$theTemp->notes = $aData['tnotes'];
		$theTemp->last_mod = $aData['tlast_mod'];
		$theTemp->keywords = $aData['keywords'];
		$theTemp->is_active	= $aData['is_active'];
		
		return $theTemp;
	}

	/**
	 *	The same as populate except it returns less data.
	 */
	public function populate_summary($aData) {
		$theTemp = new SpellTemplateTDO;
		
		$theTemp->id	=	$aData['temp_id'];
		$theTemp->name	=	$aData['temp_name'];
		$theTemp->prereq =	$aData['tprereq'];		
		$theTemp->notes = $aData['tnotes'];
		$theTemp->is_active	= $aData['is_active'];
		
		return $theTemp;
	}
}



?>