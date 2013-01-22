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
 *  $Id: nodedel.php,v 1.34 2006/01/16 09:31:57 alec Exp $
 */

$layout['pagetitle'] = 'Usunięcie konta '.$voip->GetNodeName($_GET['id']);
$SMARTY->assign('nodeid',$_GET['id']);

if (!$voip->NodeExists($_GET['id']))
{
	$body = '<H1>'.$layout['pagetitle'].'</H1><P>'.trans('Incorrect ID number').'</P>';
}else{

	if($_GET['is_sure']!=1)
	{
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>Napewno usunąć konto '.$LMS->GetNodeName($_GET['id']).' ?</P>'; 
		$body .= '<P><A HREF="?m=v_nodedel&id='.$_GET['id'].'&is_sure=1">Tak, jestem pewien</A></P>';
	}else{
		$owner = $voip->GetNodeOwner($_GET['id']);
		$voip->DeleteNode($_GET['id']);
		if($SESSION->is_set('backto'))
			header('Location: ?'.$SESSION->get('backto'));
		else
			header('Location: ?m=customerinfo&id='.$owner);
		$body = '<H1>'.$layout['pagetitle'].'</H1>';
		$body .= '<P>Konto '.$LMS->GetNodeName($_GET['id']).' zostało usunięte.</P>';
	}
}

$SMARTY->display('header.html');
$SMARTY->assign('body',$body);
$SMARTY->display('dialog.html');
$SMARTY->display('footer.html');

?>
