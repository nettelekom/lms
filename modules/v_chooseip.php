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
 *  $Id: chooseip.php,v 1.39 2006/01/16 09:31:55 alec Exp $
 */

$layout['pagetitle'] = 'Wybierz numer telefonu';

$networks = $voip->GetNetworks();

$p = isset($_GET['p']) ? $_GET['p'] : '';

if(!$p || $p == 'main')
	$js = 'var targetfield = window.parent.targetfield;';
else
	$js = '';

if (isset($_POST['netid']))
    $netid = $_POST['netid'];
elseif (isset($_GET['netid']))
    $netid = $_GET['netid'];
elseif ($SESSION->is_set('v_netid'))
    $SESSION->restore('v_netid', $netid);
else
    $netid = $networks[0]['id'];

if (isset($_POST['page']))
    $page = $_POST['page'];
elseif (isset($_GET['page']))
    $page = $_GET['page'];
elseif ($SESSION->is_set('v_ntlp.page.'.$netid))
    $SESSION->restore('v_ntlp.page.'.$netid, $page);
else
    $page = 1;

$SESSION->save('v_netid', $netid);
$SESSION->save('v_ntlp.page.'.$netid, $page);

if($p == 'main')
{
	$network = $voip->GetNetworkRecord($netid, $page, $LMS->CONFIG['phpui']['networkhosts_pagelimit']);
	$SESSION->save('v_ntlp.pages.'.$netid, $network['pages']);
}

if($p == 'down' || $p == 'top')
{
	$SESSION->restore('v_ntlp.page.'.$netid, $network['page']);
	$SESSION->restore('v_ntlp.pages.'.$netid, $network['pages']);
	if (!isset($network['pages'])) 
	{
		$network = $voip->GetNetworkRecord($netid, $page, $LMS->CONFIG['phpui']['networkhosts_pagelimit']);
		$SESSION->save('v_ntlp.pages.'.$netid, $network['pages']);
	}
}

$SMARTY->assign('part',$p);
$SMARTY->assign('js',$js);
$SMARTY->assign('networks',$networks);
$SMARTY->assign('network',$network);
$SMARTY->assign('netid',$netid);
$SMARTY->display('v_chooseip.html');

?>
