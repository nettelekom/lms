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
 *  $Id: tariffinfo.php,v 1.31 2006/01/16 09:31:58 alec Exp $
 */


$tariff = $voip->GetTariff($_GET['id']);

$layout['pagetitle'] = 'Informacje o abonamencie '.$tariff['name'];

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('tariff',$tariff);
$SMARTY->assign('tariffs',$voip->Get_Tariffs());
$SMARTY->display('v_tariffinfo.html');

?>
