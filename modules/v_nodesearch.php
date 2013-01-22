<?php

/*
 * LMS version 1.11.8 Belus
 *
 *  (C) Copyright 2001-2009 LMS Developers
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
 *  $Id: nodesearch.php,v 1.42 2009/01/13 07:45:52 alec Exp $
 */


$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(isset($_POST['search']))
	$nodesearch = $_POST['search'];

if(!isset($nodesearch))
	$SESSION->restore('v_nodesearch', $nodesearch);
else
	$SESSION->save('v_nodesearch', $nodesearch);

if(isset($_GET['search'])) 
{
	$layout['pagetitle'] = trans('SIP Search Results');

	$nodelist = $voip->GetNodeList($nodesearch);
	$listdata['total']=count($nodelist);

	$SMARTY->assign('nodelist',$nodelist);
	$SMARTY->assign('listdata',$listdata);
	
	if($listdata['total']==1)
		$SESSION->redirect('?m=v_nodeinfo&id='.$nodelist[0]['id']);
	else
		$SMARTY->display('v_nodesearchresults.html');
}
?>
