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
 *  $Id: netdel.php,v 1.33 2006/01/16 09:31:57 alec Exp $
 */

if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

$network = $voip->GetNetworkRecord($_GET['id']);

if($network['assigned'])
	$error['delete'] = TRUE;

if(!$error)
{
	if($_GET['is_sure'])
	{
		$voip->NetworkDelete($network['id']);
		$SESSION->redirect('?m='.$SESSION->get('lastmodule').'&id='.$_GET['id']);
	}
	else
	{
		$layout['pagetitle'] = trans('Removing network $a', strtoupper($network['name']));
		$SMARTY->display('header.html');
		echo '<H1>'.$layout['pagetitle'].'</H1>';
		echo '<P>'.trans('Are you sure, you want to delete that network?').'</P>';
		echo '<A href="?m=v_netdel&id='.$network['id'].'&is_sure=1">'.trans('Yes, I am sure.').'</A>';
		$SMARTY->display('footer.html');
	}
}
else
{
	$layout['pagetitle'] = trans('Info Network: $a', $network['name']);
	$SMARTY->assign('network',$network);
//	$SMARTY->assign('networks', $LMS->GetNetworks());
	$SMARTY->assign('error',$error);
	$SMARTY->display('v_netinfo.html');
}

?>
