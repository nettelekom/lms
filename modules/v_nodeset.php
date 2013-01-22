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
 *  $Id: nodeset.php,v 1.28 2006/01/16 09:31:57 alec Exp $
 */

$ownerid = isset($_GET['ownerid']) ? $_GET['ownerid'] : 0;
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if($voip->CustomerExists($ownerid))
{
	$voip->NodeSetU($ownerid, $_GET['access'], $AUTH->id);

	$backid = $ownerid;
	$redir = $SESSION->get('backto');
	if($SESSION->get('lastmodule')=='customersearch')
		$redir .= '&search=1';

	$SESSION->redirect('?'.$redir.'#'.$backid);
}

if($voip->NodeExists($id))
{
	$voip->NodeSet($id, $AUTH->id);
	$backid = $id;
}

header('Location: ?'.$SESSION->get('backto').'#'.$backid);

?>
