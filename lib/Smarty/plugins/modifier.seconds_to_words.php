<?php
/**
 * @copyright 2010 MAPIX Technologies Ltd, UK, http://mapix.com/
 * @license http://en.wikipedia.org/wiki/BSD_licenses  BSD License
 * @package Smarty
 * @subpackage PluginsModifier
 */


function smarty_modifier_seconds_to_words($seconds) {
	if ($seconds < 0) throw new Exception("Can't do negative numbers!");
	if ($seconds == 0) return "0 s";
	
	$days = intval($seconds/(24*pow(60,2)));
	if ($days > 0) {
		return $days . " d.". ($days > 1 ? "" : "");
	} else {
	        $hours = intval($seconds/pow(60,2))%24;
       	 	$minutes = intval($seconds/60)%60;
        	$seconds = $seconds%60;
        	$out = "";

		if ($hours > 0) $out .= $hours . " godz.". ($hours > 1 ? "" : "")." ";
		if ($minutes > 0) $out .= $minutes . " min". ($minutes > 1 ? "" : "")." ";
		if ($seconds > 0) $out .= $seconds . " s". ($seconds > 1 ? "" : "");
		return trim($out);
	}	
}