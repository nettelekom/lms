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
 *  $Id: nodeinfo.php,v 1.55 2006/01/16 09:31:57 alec Exp $
 */

if(!preg_match('/^[0-9]+$/',$_GET['id']))
{
	$SESSION->redirect('?m=v_nodelist');
}

if(!$voip->NodeExists($_GET['id']))
	if(isset($_GET['ownerid']))
	{
		$SESSION->redirect('?m=customerinfo&id='.$_GET['ownerid']);
	}
	else
	{
		$SESSION->redirect('?m=v_nodelist');
	}


$nodeid = $_GET['id'];
$customerid = $voip->GetNodeOwner($nodeid);

include(MODULES_DIR . '/customer.inc.php');
include(MODULES_DIR.'/customer.voip.inc.php');

$nodeinfo=$voip->GetNode($nodeid);
$nodeinfo['ownerid']=$customerid;
$nodeinfo['id']=$nodeinfo['id_ast_sip'];
$nodeinfo['createdby'] = $LMS->GetUserName($nodeinfo['creatorid']);
if($nodeinfo['modifierid']) $nodeinfo['modifiedby'] = $LMS->GetUserName($nodeinfo['modifierid']);

$sub=$voip->get_id_subscriptions();
$tar=$voip->get_id_tariffs();
$nodeinfo['id_subscriptions']=$sub[$nodeinfo['id_subscriptions']];
$nodeinfo['id_tariffs']=$tar[$nodeinfo['id_tariffs']];
$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['ownerid']))
	$SESSION->save('backto', $SESSION->get('backto').'&ownerid='.$ownerid);

$layout['pagetitle'] = 'Informacje o koncie '.$nodeinfo['name'];

$SMARTY->assign('nodedata',$nodeinfo);
$SMARTY->display('v_nodeinfo.html');
?>
