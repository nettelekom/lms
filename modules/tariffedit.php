<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2017 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */

if(!$LMS->TariffExists($_GET['id']))
{
	$SESSION->redirect('?m=tarifflist');
}

if(isset($_GET['set'])) {
	$LMS->TariffSet($_GET['id']);
	$SESSION->redirect('?m=tarifflist');
}

if(isset($_POST['tariff']))
{
	$tariff = $_POST['tariff'];
	$limit = isset($_POST['limit']) ? $_POST['limit'] : array();

	foreach($tariff as $key => $value)
		$tariff[$key] = trim($value);

	$tariff['id'] = $_GET['id'];
	$tariff['value'] = str_replace(',','.',$tariff['value']);

	if (!preg_match('/^[-]?[0-9.,]+$/', $tariff['value']))
		$error['value'] = trans('Incorrect value!');

	if ($tariff['name'] == '')
		$error['name'] = trans('Subscription name required!');
	else if (!$error) {
	     if ($DB->GetOne('SELECT id FROM tariffs WHERE name = ? AND value = ?
	        AND period = ? AND id <> ?',
            array($tariff['name'], str_replace(',', '.', $tariff['value']),
                $tariff['period'] == '' ? NULL : $tariff['period'], $tariff['id']))
        ) {
	        $error['name'] = trans('Subscription with specified name and value already exists!');
	    }
	}

	if ($tariff['datefrom'] == '')
		$tariff['from'] = 0;
	elseif (preg_match('/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/', $tariff['datefrom'])) {
		list ($y, $m, $d) = explode('/', $tariff['datefrom']);
		if (checkdate($m, $d, $y))
			$tariff['from'] = mktime(0, 0, 0, $m, $d, $y);
		else
			$error['datefrom'] = trans('Incorrect effective start time!');
	} else
		$error['datefrom'] = trans('Incorrect effective start time!');

	if ($tariff['dateto'] == '')
		$tariff['to'] = 0;
	elseif (preg_match('/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/', $tariff['dateto'])) {
		list ($y, $m, $d) = explode('/', $tariff['dateto']);
		if (checkdate($m, $d, $y))
			$tariff['to'] = mktime(23, 59, 59, $m, $d, $y);
		else
			$error['dateto'] = trans('Incorrect effective end time!');
	} else
		$error['dateto'] = trans('Incorrect effective end time!');

	if ($tariff['to'] != 0 && $tariff['from'] != 0 && $tariff['to'] < $tariff['from'])
		$error['dateto'] = trans('Incorrect date range!');

	$items = array('uprate', 'downrate', 'upceil', 'downceil', 'climit', 'plimit', 'dlimit');

	foreach($items as $item)
	{
	        if($tariff[$item]=='')
	                $tariff[$item] = 0;
	        elseif(!preg_match('/^[0-9]+$/', $tariff[$item]))
	                $error[$item] = trans('Integer value expected!');
	}

	if(($tariff['uprate'] < 8 || $tariff['uprate'] > 500000) && $tariff['uprate'] != 0)
		$error['uprate'] = trans('This field must be within range 8 - 500000');
	if(($tariff['downrate'] < 8 || $tariff['downrate'] > 500000) && $tariff['downrate'] != 0)
		$error['downrate'] = trans('This field must be within range 8 - 500000');
	if(($tariff['upceil'] < 8 || $tariff['upceil'] < $tariff['uprate']) && $tariff['upceil'] != 0)
		$error['upceil'] = trans('This field must be greater than 8 and greater than upload rate');
	if(($tariff['downceil'] < 8 || $tariff['downceil'] < $tariff['downrate']) && $tariff['downceil'] != 0)
		$error['downceil'] = trans('This field must be greater than 8 and greater than download rate');

	$items = array('uprate_n', 'downrate_n', 'upceil_n', 'downceil_n', 'climit_n', 'plimit_n');

        foreach($items as $item)
	{
	        if($tariff[$item]=='')
	                $tariff[$item] = NULL;
	        elseif(!preg_match('/^[0-9]+$/', $tariff[$item]))
	                $error[$item] = trans('Integer value expected!');
	}

	if(($tariff['uprate_n'] < 8 || $tariff['uprate_n'] > 500000) && $tariff['uprate_n'])
	        $error['uprate_n'] = trans('This field must be within range 8 - 500000');
	if(($tariff['downrate_n'] < 8 || $tariff['downrate_n'] > 500000) && $tariff['downrate_n'])
	        $error['downrate_n'] = trans('This field must be within range 8 - 500000');
	if(($tariff['upceil_n'] < 8 || $tariff['upceil_n'] < $tariff['uprate']) && $tariff['upceil_n'])
	        $error['upceil_n'] = trans('This field must contain number greater than 8 and greater than upload rate');
	if(($tariff['downceil_n'] < 8 || $tariff['downceil_n'] < $tariff['downrate']) && $tariff['downceil_n'])
	        $error['downceil_n'] = trans('This field must contain number greater than 8 and greater than download rate');

	if(!isset($tariff['taxid']))
		$tariff['taxid'] = 0;

	$items = array('domain_limit', 'alias_limit');
	foreach ($ACCOUNTTYPES as $typeidx => $type) {
		$items[] = $type['alias'] . '_limit';
		$items[] = 'quota_' . $type['alias'] . '_limit';
	}

	foreach ($items as $item) {
	    if(isset($limit[$item]))
		    $tariff[$item] = NULL;
	    elseif(!preg_match('/^[0-9]+$/', $tariff[$item]))
	        $error[$item] = trans('Integer value expected!');
	}

	if (!$error) {
		$LMS->TariffUpdate($tariff);
		$SESSION->redirect('?m=tariffinfo&id='.$tariff['id']);
	}
}
else {
	$tariff = $LMS->GetTariff($_GET['id']);

	if ($tariff['dateto'])
		$tariff['dateto'] = date('Y/m/d', $tariff['dateto']);

	if ($tariff['datefrom'])
		$tariff['datefrom'] = date('Y/m/d', $tariff['datefrom']);
}

$layout['pagetitle'] = trans('Subscription Edit: $a',$tariff['name']);

$SMARTY->assign('voip_tariffs'    , $LMS->getVoipTariffs());
$SMARTY->assign('voip_tariffrules', $LMS->getVoipTariffRuleGroups());
$SMARTY->assign('tariff'          , $tariff);
$SMARTY->assign('taxeslist'       , $LMS->GetTaxes());
$SMARTY->assign('numberplanlist'  , $LMS->GetNumberPlans(array(
	'doctype' => DOC_INVOICE,
)));
$SMARTY->assign('error'           , $error);
$SMARTY->display('tariff/tariffedit.html');

?>
