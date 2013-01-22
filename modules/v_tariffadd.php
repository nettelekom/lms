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
 *  $Id: tariffadd.php,v 1.48 2006/01/16 09:31:58 alec Exp $
 */

$tariffadd = $_POST['tariffadd'];

if(isset($tariffadd))
{
	foreach($tariffadd as $key => $value)
		$tariffadd[$key] = trim($value);

	if($tariffadd['name']=='' && $tariffadd['amount']=='')
	{
		$SESSION->redirect('Location: ?m=v_tarifflist');
	}

	$tariffadd['amount'] = str_replace(',','.',$tariffadd['amount']);

	if(!(ereg('^[-]?[0-9.,]+$',$tariffadd['amount'])))
		$error['amount'] = 'Błędna wartość';
/*
	if($tariffadd['uprate']=='') $tariffadd['uprate'] = 0;
	if($tariffadd['downrate']=='') $tariffadd['downrate'] = 0;
	if($tariffadd['upceil']=='') $tariffadd['upceil'] = 0;
	if($tariffadd['downceil']=='') $tariffadd['downceil'] = 0;
	if($tariffadd['climit']=='') $tariffadd['climit'] = 0;
	if($tariffadd['plimit']=='') $tariffadd['plimit'] = 0;

	if(!ereg('^[0-9]+$', $tariffadd['uprate']))
		$error['uprate'] = trans('Integer value expected!');
	if(!ereg('^[0-9]+$', $tariffadd['downrate']))
		$error['downrate'] = trans('Integer value expected!');
	if(!ereg('^[0-9]+$', $tariffadd['upceil']))
		$error['upceil'] = trans('Integer value expected!');
	if(!ereg('^[0-9]+$', $tariffadd['downceil']))
		$error['downceil'] = trans('Integer value expected!');
	if(!ereg('^[0-9]+$', $tariffadd['climit']))
		$error['climit'] = trans('Integer value expected!');
	if(!ereg('^[0-9]+$', $tariffadd['plimit']))
		$error['plimit'] = trans('Integer value expected!');

	if(($tariffadd['uprate'] < 8 || $tariffadd['uprate'] > 4096) && $tariffadd['uprate'] != 0)
		$error['uprate'] = trans('This field must be within range 8 - 4096');
	if(($tariffadd['downrate'] < 8 || $tariffadd['downrate'] > 4096) && $tariffadd['downrate'] != 0)
		$error['downrate'] = trans('This field must be within range 8 - 4096');
	if(($tariffadd['upceil'] < 8 || $tariffadd['upceil'] < $tariffadd['uprate']) && $tariffadd['upceil'] != 0)
		$error['upceil'] = trans('This field must contain number greater than 8 and greater than upload rate');
	if(($tariffadd['downceil'] < 8 || $tariffadd['downceil'] < $tariffadd['downrate']) && $tariffadd['downceil'] != 0)
		$error['downceil'] = trans('This field must contain number greater than 8 and greater than download rate');
*/
	if($tariffadd['name'] == '')
		$error['name'] = 'Nazwa jest wymagana';
	else
		if($voip->GetTariffIDByName($tariffadd['name']))
			$error['name'] = 'Abonament o tej nazwie już istnieje';

	if(!$tariffadd['tax'])
		$tariffadd['tax'] = 22;
		
	if(!$error)
	{
		$voip->TariffAdd($tariffadd);
		$SESSION->redirect('?m=v_tarifflist');
	}
	
}

$layout['pagetitle'] = 'Nowy abonament';

$SMARTY->assign('error',$error);
$SMARTY->assign('tariffadd',$tariffadd);

$SMARTY->display('v_tariffadd.html');

?>
