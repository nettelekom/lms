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
 *  $Id: netinfo.php,v 1.37 2006/01/16 09:31:57 alec Exp $
 */

if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;

if($SESSION->is_set('v_ntlp.'.$_GET['id']) && !isset($_GET['page']))
	$SESSION->restore('v_ntlp.'.$_GET['id'], $page);

$SESSION->save('v_ntlp.'.$_GET['id'], $page);

$network = $voip->GetNetworkRecord($_GET['id'], $page, $LMS->CONFIG['phpui']['networkhosts_pagelimit']);

$layout['pagetitle'] = 'Informacje o strefie '.$network['name'];

$SMARTY->assign('network', $network);
$SMARTY->display('v_netinfo.html');

?>
