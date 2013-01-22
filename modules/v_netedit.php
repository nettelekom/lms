<?php

/*
 * LMS version 1.9.1 Jumar
 *
 *  (C) Copyright 2001-2006 LMS Developers
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
 *  $Id: netedit.php,v 1.49 2006/01/16 09:31:57 alec Exp $
 */

if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

if($SESSION->is_set('v_ntlp.'.$_GET['id']) && ! isset($_GET['page']))
	$SESSION->restore('v_ntlp.'.$_GET['id'], $_GET['page']);

$SESSION->save('v_ntlp.'.$_GET['id'], $_GET['page']);
	
$network = $voip->GetNetworkRecord($_GET['id'],$_GET['page'], $LMS->CONFIG['phpui']['networkhosts_pagelimit']);

if(isset($_POST['networkdata']))
{
	$networkdata = $_POST['networkdata'];

	foreach($networkdata as $key => $value)
		$networkdata[$key] = trim($value);
		
	$networkdata['id'] = $_GET['id'];
	if($networkdata['name']=='')
		$error['name'] = trans('Network name is required!');
	if($networkdata['start'] == '' || !preg_match('/^0[0-9]{9}$/', $networkdata['start']))
		$error['start'] = 'Niewłaściwy numer';
	if($networkdata['end'] == '' || !preg_match('/^0[0-9]{9}$/', $networkdata['end']))
		$error['end'] = 'Niewłaściwy numer';
	if($networkdata['start'] >= $networkdata['end'] || $networkdata['end'] - $networkdata['start'] > 1000)
		$error['end'] = 'Niewłaściwy numer';

	if(!$error)
	{
		$voip->NetworkUpdate($networkdata);
		$SESSION->redirect('?m=v_netinfo&id='.$networkdata['id']);
	}	

	$network['address'] = $networkdata['address'];
	$network['size'] = $networkdata['size'];
}
$layout['pagetitle'] = trans('Network Edit: $a',$network['name']);
$SMARTY->assign('unlockedit',TRUE);
$SMARTY->assign('network',$network);
$SMARTY->assign('error',$error);
$SMARTY->display('v_netinfo.html');
?>
