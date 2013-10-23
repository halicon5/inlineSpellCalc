<?php
class SpellHTML {
	private static 	$tr_tag = "<tr valign=\"top\">";
	private function __construct() {
		// do not instantiate
	}
	
	public static function getDetailMultiple($theSpells) {
		$html = "";
		foreach ($theSpells as $spell) {
			$html .= self::getDetail($spell);
		}
		return $html;	
	}
	
	
	public static function getDetail(SpellTDO $spell) {			
		$html = "";
		
		if ($spell->temp_id > 0) {
			$tempSVC = new SpellTemplateSVC;
			$temp = $tempSVC->get_by_id($spell->temp_id);
		} else {
			$temp = new SpellTemplateTDO;
		}
		
		self::parse_ref_links($spell);
		self::parse_ref_links($temp);

		$disciplines = self::get_discipline_string($spell);
		$disciplines = parse_cross_ref_links($disciplines);
		
		$spell_types = self::get_spell_type_string($spell);
		$spell_types = parse_cross_ref_links($spell_types);

		$html .= "<table class=\"spell_detail\" border=\"0\" >\n";
		$html .= self::$tr_tag."<td colspan=\"3\"><h3 class=\"spell_name\">".$spell->name."</h3></td></tr>\n";

		$html .= self::get_row("Disciplines", $disciplines);
		$html .= self::get_row("Spell Types", $spell_types);
		$html .= self::get_row("Pre-requisites", $spell->prereq, $temp->prereq);
		$html .= self::get_row("Components", $spell->components, $temp->components);
		$html .= self::get_row("Minimum Cost", $spell->min_cost, $temp->min_cost);

		$html .= self::get_row("", "<b>(Strain)</b>", "", "<b>(TAV)</b>");
		$html .= self::get_row("Base", $spell->base_cost, $temp->base_cost, $spell->base_tav, $temp->base_tav);
		$html .= self::get_row("EPOT", $spell->epot_cost, $temp->epot_cost, $spell->epot_tav, $temp->epot_tav);
		$html .= self::get_row("Area of Effect", $spell->aoe, $temp->aoe, $spell->aoe_tav, $temp->aoe_tav);
		$html .= self::get_row("Targets", $spell->targ, $temp->targ, $spell->targ_tav, $temp->targ_tav);
		$html .= self::get_row("Range", $spell->range, $temp->range, $spell->range_tav, $temp->range_tav);
		$html .= self::get_row("Duration", $spell->dur, $temp->dur, $spell->dur_tav, $temp->dur_tav);

		$html .= self::get_row("Karma", $spell->karma, $temp->karma);
		$html .= self::get_row("Drain", $spell->drain, $temp->drain);
		$html .= self::get_row("Surge", $spell->surge, $temp->surge);
		
		$html .= self::get_row("Damage", $spell->damage, $temp->damage);
		$html .= self::get_row("Staging", $spell->staging, $temp->staging);
		$html .= self::get_row("Resist", $spell->resist, $temp->resist);
		
		$html .= "<tr><td id=\"spell".$spell->id."\" colspan=\"4\"></td></tr>"; 

		$html .= self::get_row("Description", $spell->description);
		$html .= self::get_row("Effect", $spell->effect, $temp->effect);
		$html .= self::get_row("Limits", $spell->limits, $temp->limits);
		$html .= self::get_row("Special", $spell->special, $temp->special);
	
		$html .= "</table>";

		$html .= self::createSpellCalculator($spell, $temp);

		return $html;
	}


	public static function createSpellCalculator(SpellTDO $spell, SpellTemplateTDO $temp) {
		$html = "";

		if ($temp->sCalcVars) {
			$html .= "<script type='text/javascript'>";
			$html .= "SCManager.spellStack.push(new sCalc('spell".$spell->id."', 
				'spell".$spell->id."',
				atob('".base64_encode($temp->sCalcVars)."'),
				atob('".base64_encode($temp->sCalcForm)."'),
				atob('".base64_encode($temp->sCalcCalc)."'),
				atob('".base64_encode($temp->sCalcRep)."'),
				'',
				sCalcUserData,
				'horiz'));";
			$html .= "</script>";
		}

		return $html;
	}


	/**
	 *	If the $print_select flag is set, then checkboxes are displayed
	 *	for a form that takes the user to a display of the first 25
	 *  spells on their list.
	 */
	public static function getList($theSpells, $columns = 2, $print_select = false) {
		$html = "";
		if (!is_array($theSpells) ) {
			return $html;
		}
		
		if ($print_select) {
			$html .= "<form name='spell_select' action='spell_display.php' method='get'>\n";		
			$html .= "<input type='hidden' name='selected' value='1' />\n";
			$html .= "<input type='submit' value='Get detailed list of selected spells' />\n";
		}
		$html .= "<table border=\"0\" class=\"spell_list\"><tr valign=\"top\"><td class=\"spell_column\">";

		$cols = ceil(count($theSpells)/$columns);
		$count = 0;
		
		foreach ($theSpells as $spell) {
			if ($print_select) {
				$html .= "<input type='checkbox' name=\"sil[]\" value=\"".$spell->id."\" />\n";
			}
			$html .= "<a href=\"".$_SERVER['PHP_SELF']."?spell_id=".$spell->id."\">".$spell->name."</a><br />\n";
			$count++;
			if ($count % $cols == 0 && count($theSpells) != $count ) $html .= "</td><td class=\"spell_column\">\n";
		}
		$html .= "</td></tr></table>\n";

		if ($print_select) {
			$html .= "<input type='submit' value='Get detailed list of selected spells' />\n";
			$html .= "</form>";
		}
		return $html;
	}




	private function get_row($head, $s1, $t1 = "", $s2 = "", $t2 = "" ) {
		if ( self::is_row_printable($s1, $t1, $s2, $t2) ) {
			$row = self::$tr_tag."<td class=\"spell_topic\">".$head."</td>";

			($t1 != "") ?
				$string1 = tparse($s1, $t1):
				$string1 = $s1;
				
			($t2 != "") ?
				$string2 = tparse($s2, $t2):
				$string2 = $s2;
			

			if ($string2 == "") {
				$row .= "<td colspan=\"2\">".$string1."</td></tr>\n";
			} else {
				$row .= "<td>".$string1."</td><td>".$string2."</td></tr>\n";
			}

			return $row;
		}
	}

	
	private function parse_ref_links($arg) {
		$arg->prereq 		= parse_cross_ref_links( $arg->prereq );
		$arg->components 	= parse_cross_ref_links( $arg->components );
		$arg->effect 		= parse_cross_ref_links( $arg->effect );
		$arg->limits 		= parse_cross_ref_links( $arg->limits );
		$arg->special 		= parse_cross_ref_links( $arg->special );
		$arg->resist		= parse_cross_ref_links( $arg->resist );

		if ( get_class($arg) == "SpellTDO" )
			$arg->description = parse_cross_ref_links( $arg->description );

	}


	private function get_discipline_string(SpellTDO $spell, $punct = ";", $links = true) {
		$line = "";
		if ($links) {
			foreach ($spell->disciplines as $disc) {
				$line .= "<a href=\"spell_display.php?display=list&disc_id=".$disc->id."\">".$disc->name."</a>".$punct." ";
			}
			$line = parse_cross_ref_links($line);
		} else {
			foreach ($spell->disciplines as $disc) {
				$line .= $disc->name.$punct." ";		
			}
		}
		return substr($line, 0, (strlen($line) - 2) );
	}

	
	private function get_spell_type_string(SpellTDO $spell, $punct = ";", $links = true) {
		$line = "";
		if ($links) {
			foreach ($spell->spell_types as $type) {
				$line .= "<l:ref>".$type->name."</l:ref>".$punct." ";
			}
			$line = parse_cross_ref_links($line);
		} else {
			foreach ($spell->spell_types as $type) {
				$line .= $type->name.$punct." ";
			}
		}	
		return substr($line, 0, (strlen($line) - 2) );
	}	



	private function is_row_printable($st1, $st2 = "", $st3 = "", $st4 = "") {
		return ($st1 != "" || $st2 != "" || $st3 != "" || $st4 != "") ? true: false;
	}


	public function getSpellMenu() {
		$html = "<div class=\"skill_menu\">\n";
		$html .= "<a href=\"".$_SERVER["PHP_SELF"]."?display=list_all\">All Spells, Alphabetical List</a><br />";
		$html .= "<a href=\"".$_SERVER["PHP_SELF"]."\">All Spell Disciplines</a><br />";
		$html .= "</div>";
		return $html;	
	}

	public function getDiscFilterForm($default = "") {
		$discSVC = new DisciplineSVC;
		$theDiscs = $discSVC->get_all_sum();
		
		$html = "<form name=\"select_discipline\" action=\"spell_display.php\" method=\"get\" >\n";
		$html .= "<input name=\"display\" value=\"list_all\" type=\"hidden\" />\n";
		$html .= "<select name=\"disc_filter\"/>\n";
		$html .= "<option value=\"all\"/>Display All</option>";
		if (is_array($theDiscs) ) {
			foreach ($theDiscs as $disc) {
				$html .= "<option value=\"".$disc->id."\" ";
				if ($disc->id == $default) {
					$html .= "selected=\"selected\" ";
				}
				$html .= ">".$disc->name."</option>\n";
			}
		}
		$html .= "</select><input type=\"submit\" value=\"Filter by Discipline\" /></form>\n";
		
		return $html;
	}


}
?>