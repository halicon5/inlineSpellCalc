<?php
class SpellDAO_mysql {
	public function get_all_spells($aActive = true, $aDate = "0000-00-00") {
		$sql = "SELECT *
				FROM spells\n";
		
		if ($aActive == true || $aDate != "0000-00-00") {
			$sql .= "WHERE is_active = 'Y'\n";
			if ($aDate != "0000-00-00") $sql .= "AND last_mod >= '".$aDate."'\n";
		}

		$sql .= "ORDER BY spell_name";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row) {
				$theRecords[] = $this->populate($row);
			}
		} 

		return $theRecords;

	}	

	/**
	 *	same as get_all_spells except it only gets a summary of data
	 */
	public function get_all_spells_sum($aActive = true, $aDate = "0000-00-00") {
		$sql = "SELECT spell_id, spell_name, prereq, notes, last_mod, is_active
				FROM spells\n";

		if ($aActive == true || $aDate != "0000-00-00") {
			$sql .= "WHERE is_active = 'Y'\n";
			if ($aDate != "0000-00-00") $sql .= "AND last_mod >= '".$aDate."'\n";
		}
		
		$sql .= "ORDER BY spell_name";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row) {
				$theRecords[] = $this->populate_summary($row);
			}
		} 

		return $theRecords;
	}


	public function get_spell_by_id($aId) {
		$sql = "SELECT *
				FROM spells
				WHERE spell_id = '$aId'";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		return (count($theData) == 1) ? $this->populate($theData[0]) : false;
	}




	public function get_spells_by_id_list($aList, $limit = -1) {
		$theList = prepare_where_in_list($aList, $limit);

		if ( $theList != -1 ) {
			$sql = "SELECT *
				FROM spells
				WHERE spell_id IN (".$theList.")
				ORDER BY spell_name";
		} else {
			$theRecords = array();
			return $theRecords;	
		}
			
		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row) {
				$theRecords[] = $this->populate($row);
			}
		} 

		return $theRecords;	
	}
	


	public function get_spell_by_name($aName) {
		$sql = "SELECT *
				FROM spells
				WHERE spell_name = '$aName'";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row) {
				$theRecords[] = $this->populate($row);
			}
		} 

		return $theRecords;
	}


	public function get_spell_sums_by_discipline_id($aId) {
		$sql = "SELECT spells.spell_id, spells.spell_name, spells.prereq, spells.notes, spells.last_mod, spells.is_active
				FROM spells, discipline_cross
				WHERE spells.spell_id = discipline_cross.spell_id
					AND discipline_cross.disc_id = '".$aId."'
					AND spells.is_active = 'Y'
				ORDER BY spells.spell_name";

		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row)
				$theRecords[] = $this->populate_summary($row);
		}
		
		return $theRecords;		
	}

	public function get_spells_by_discipline_id($aId) {
		$sql = "SELECT spells.*
				FROM spells, discipline_cross
				WHERE spells.spell_id = discipline_cross.spell_id
					AND discipline_cross.disc_id = '".$aId."'
					AND spells.is_active = 'Y'
				ORDER BY spells.spell_name";
		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$theRecords = array();
		
		if (count($theData) > 0) {
			foreach ($theData as $row)
				$theRecords[] = $this->populate($row);
		}
		
		return $theRecords;						
	}

	

	private function get_id_array($sql, $prefix) {
		$column_name = $prefix."_id";
		$result = DBcon::Run_Query($sql);
		$theData = DBcon::Result_to_Array($result);
		$ids = array();
		foreach($theData as $row) {
			$ids[] = $row[$column_name];
		}
		return $ids;	
	}
	
	
	
	

	public function get_genres(SpellTDO $spell)	{
		$genService = new GenreSVC;
		$genService->get_genres_by_spell($spell);
	}

	public function get_spell_types(SpellTDO $spell) {
		$typeService = new SpellTypeSVC;
		$typeService->get_spell_types_by_spell($spell);
	}

	public function get_disciplines(SpellTDO $spell) {
		$discService = new DisciplineSVC;
		$discService->get_disciplines_by_spell($spell);
	}
	
	public function get_campaigns(SpellTDO $spell) {
		$campService = new CampaignSVC;
		$campService->get_campaigns_by_spell($spell);
	}
	
	

	







	/**
	 *	@param	$spell		SpellTDO
	 */
	public function update_all_cross_references(SpellTDO $spell, $cross_refs) {
		$this->update_genre_cross_refs($spell, $cross_refs['genres']);
		$this->update_spell_type_cross_refs($spell, $cross_refs['spell_types']);
		$this->update_discipline_cross_refs($spell, $cross_refs['disciplines']);
		$this->update_campaign_cross_refs($spell, $cross_refs['campaigns']);
	}
	

	public function update_genre_cross_refs(SpellTDO $spell, $genres) {
		$table_name = "spell_genre_cross";
		$this->clear_cross_refs($spell->id, $table_name);
		if ( is_array($genres) ) {
			foreach ($genres as $genre) {
				$sql = "INSERT INTO ".$table_name."
						(	spell_id,
							genre_id )
						VALUES
						(	".$spell->id.",
							".$genre.")";
							
				DBcon::Run_Query($sql);
			}
		}
	}

	public function update_spell_type_cross_refs(SpellTDO $spell, $types) {
		$table_name = "spell_type_cross";
		$this->clear_cross_refs($spell->id, $table_name);
		if ( is_array($types) ) {
			foreach ($types as $type) {
				$sql = "INSERT INTO ".$table_name."
						(	spell_id,
							type_id )
						VALUES
						(	".$spell->id.",
							".$type.")";
							
				DBcon::Run_Query($sql);
			}
		}
	}

	public function update_campaign_cross_refs(SpellTDO $spell, $camps) {
		$table_name = "spell_camp_cross";
		$this->clear_cross_refs($spell->id, $table_name);
		if ( is_array($camps) ) {
			foreach ($camps as $campaign) {
				$sql = "INSERT INTO ".$table_name."
						(	spell_id,
							camp_id )
						VALUES
						(	".$spell->id.",
							".$campaign.")";
							
				DBcon::Run_Query($sql);
			}
		}
	}

	public function update_discipline_cross_refs(SpellTDO $spell, $disciplines) {
		$table_name = "discipline_cross";
		$this->clear_cross_refs($spell->id, $table_name);
		if ( is_array($disciplines) ) {
			foreach ($disciplines as $disc) {
				$sql = "INSERT INTO ".$table_name."
						(	spell_id,
							disc_id )
						VALUES
						(	".$spell->id.",
							".$disc.")";
							
				DBcon::Run_Query($sql);
			}
		}
	}

		
	private function clear_cross_refs($id, $table_name) {
		$sql = "DELETE FROM ".$table_name."
				WHERE spell_id = '".$id."'";	
		$result = DBcon::Run_Query($sql);
	}
		

	






	public function add_spell(SpellTDO $aData) {
		$sql = "INSERT INTO spells
				(	spell_id,
					spell_name,
					temp_id,					
					prereq,
					components,					
					min_cost,
					base_cost,
					base_tav,
					epot_cost,
					epot_tav,
					aoe,
					aoe_tav,
					targ,
					targ_tav,
					range,
					range_tav,
					dur,
					dur_tav,
					karma,
					surge,
					drain,
					damage,
					staging,
					resist,
					description,
					effect,
					limits,
					special,
					last_mod,
					notes,
					keywords,
					is_active )
				VALUES
				(	'',
					'".$aData->name."',
					'".$aData->temp_id."',
					'".$aData->prereq."',
					'".$aData->components."',
					'".$aData->min_cost."',
					'".$aData->base_cost."',
					'".$aData->base_tav."',
					'".$aData->epot_cost."',
					'".$aData->epot_tav."',
					'".$aData->aoe."',
					'".$aData->aoe_tav."',
					'".$aData->targ."',
					'".$aData->targ_tav."',
					'".$aData->range."',
					'".$aData->range_tav."',
					'".$aData->dur."',
					'".$aData->dur_tav."',
					'".$aData->karma."',
					'".$aData->surge."',
					'".$aData->drain."',
					'".$aData->damage."',
					'".$aData->staging."',
					'".$aData->resist."',
					'".$aData->description."',
					'".$aData->effect."',
					'".$aData->limits."',
					'".$aData->special."',
					'".get_timestamp()."',
					'".$aData->notes."',
					'".$aData->keywords."',
					'".$aData->is_active."'
				)";
				
		$result = DBcon::Run_Query($sql);
		$aData->id = mysql_insert_id();
		SearchTools::index_spell($aData);

	}
	
	
	public function update_spell(SpellTDO $aData) {
		$sql = "UPDATE spells
				SET	spell_name	= '".$aData->name."',
					temp_id 	= '".$aData->temp_id."',
					
					prereq		= '".$aData->prereq."',
					components	= '".$aData->components."',

					min_cost	= '".$aData->min_cost."',
					base_cost	= '".$aData->base_cost."',
					base_tav	= '".$aData->base_tav."',
					epot_cost	= '".$aData->epot_cost."',
					epot_tav	= '".$aData->epot_tav."',
					aoe			= '".$aData->aoe."',
					aoe_tav		= '".$aData->aoe_tav."',
					targ		= '".$aData->targ."',
					targ_tav	= '".$aData->targ_tav."',
					range		= '".$aData->range."',
					range_tav	= '".$aData->range_tav."',
					dur			= '".$aData->dur."',
					dur_tav		= '".$aData->dur_tav."',
					karma		= '".$aData->karma."',
					
					surge		= '".$aData->surge."',
					drain		= '".$aData->drain."',

					damage		= '".$aData->damage."',
					staging		= '".$aData->staging."',
					resist		= '".$aData->resist."',

					description	= '".$aData->description."',
					effect		= '".$aData->effect."',
					limits		= '".$aData->limits."',
					special		= '".$aData->special."',

					last_mod	= '".get_timestamp()."',
					keywords	= '".$aData->keywords."',
					notes		= '".$aData->notes."',
					is_active	= '".$aData->is_active."'
				WHERE spell_id = '".$aData->id."'
				";

		$result = DBcon::Run_Query($sql);
		SearchTools::index_spell($aData);
	}


	public function remove_spell($aId) {
		$sql = "DELETE FROM spells
				WHERE spell_id = '".$aId."'";
				
		$result = DBcon::Run_Query($sql);
		
		$this->clear_cross_refs($aId, "spell_genre_cross");
		$this->clear_cross_refs($aId, "spell_camp_cross");
		$this->clear_cross_refs($aId, "discipline_cross");
		$this->clear_cross_refs($aId, "spell_type_cross");
	}
	

	
	public function populate($aData) {
		$theRecord = new SpellTDO;
		
		$theRecord->id	=	$aData['spell_id'];
		$theRecord->name	=	$aData['spell_name'];
		$theRecord->temp_id	=	$aData['temp_id'];
		
		$theRecord->prereq	=	$aData['prereq'];
		$theRecord->components	=	$aData['components'];

		$theRecord->min_cost	=	$aData['min_cost'];
		$theRecord->base_cost	=	$aData['base_cost'];
		$theRecord->base_tav	=	$aData['base_tav'];
		$theRecord->epot_cost	=	$aData['epot_cost'];
		$theRecord->epot_tav	=	$aData['epot_tav'];
		$theRecord->aoe		=	$aData['aoe'];
		$theRecord->aoe_tav	=	$aData['aoe_tav'];
		$theRecord->targ	=	$aData['targ'];
		$theRecord->targ_tav	=	$aData['targ_tav'];
		$theRecord->range	=	$aData['range'];
		$theRecord->range_tav	=	$aData['range_tav'];
		$theRecord->dur	=	$aData['dur'];
		$theRecord->dur_tav	=	$aData['dur_tav'];
		$theRecord->karma	=	$aData['karma'];

		$theRecord->surge	=	$aData['surge'];
		$theRecord->drain	=	$aData['drain'];

		$theRecord->damage	=	$aData['damage'];
		$theRecord->staging	=	$aData['staging'];
		$theRecord->resist	=	$aData['resist'];

		$theRecord->description	=	$aData['description'];
		$theRecord->effect	=	$aData['effect'];
		$theRecord->limits	=	$aData['limits'];
		$theRecord->special	=	$aData['special'];
		
		$theRecord->last_mod	=	$aData['last_mod'];
		$theRecord->notes	=	$aData['notes'];
		$theRecord->keywords =	$aData['keywords'];
		$theRecord->is_active	= $aData['is_active'];
		
		return $theRecord;
	}
	
	public function populate_summary($aData) {
		$sql = "SELECT spell_id, spell_name, prereq, notes, is_active
				FROM spells\n";
				
		$theRecord = new SpellTDO;
		
		$theRecord->id	=	$aData['spell_id'];
		$theRecord->name	=	$aData['spell_name'];
		$theRecord->prereq	=	$aData['prereq'];
		$theRecord->notes	=	$aData['notes'];
		$theRecord->last_mod	=	$aData['last_mod'];
		$theRecord->is_active	= $aData['is_active'];

		return $theRecord;
	}
	
}	

?>