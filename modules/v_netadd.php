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
 *  $Id: netadd.php,v 1.47 2006/01/16 09:31:57 alec Exp $
 */

if(isset($_POST['netadd']))
{
	$netadd = $_POST['netadd'];
	
	foreach($netadd as $key=>$value)
	{
		$netadd[$key] = trim($value);
	}

	if($netadd['name'] == '')
		$error['name'] = trans('Network name is required!');
	
	if($netadd['start'] == '' || !preg_match('/^0[0-9]{9}$/', $netadd['start']))
		$error['start'] = 'Niewłaściwy numer';
	if($netadd['count'] == '' || !preg_match('/^[0-9]+$/', $netadd['count']) || $netadd['count'] > 1000)
		$error['count'] = 'Niewłaściwy zakres';

	if(!$error)
	{
		$SESSION->redirect('?m=v_netinfo&id='.$voip->NetworkAdd($netadd));
	}

	$SMARTY->assign('error', $error);
	$SMARTY->assign('netadd', $netadd);
}

$layout['pagetitle'] = trans('New Network');
$SMARTY->display('v_netadd.html');

?>
