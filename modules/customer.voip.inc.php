<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id$
 */

if($customerinfo['isvoip'] == 1)
{
	$v = TRUE;
	$SMARTY->assign('isvoip', TRUE);

        $customerinfo = $voip->GetCustomer($customerinfo, $customerid);
	if($setl = $CONFIG['voip']['voip_set_remb'])
	{
		if(date('Y-m-d', strtotime('+'.$setl.' month'))>=$customerinfo['voipkoniecum'])
			$SMARTY->assign('setl',$setl);
	}
        $customersip = $voip->GetCustomerNodes($customerid);
	$customersip['ownerid'] = $_GET['id'];
	$SMARTY->assign('customersip', $customersip);
	$SMARTY->assign('cdr', $voip->GetLastUserCdr($customerid));
	if($customerinfo['woj'] && $customerinfo['pow'] && $customerinfo['mia'])
	{
		$tmp = $voip->list_woj();
		$geoloc = $tmp[$customerinfo['woj']];
		$tmp = $voip->list_pow($customerinfo['woj']);
		$geoloc .= ' - &gt; ' . $tmp[$customerinfo['pow']];
		$tmp = $voip->list_mia($customerinfo['pow']);
		$geoloc .= ' - &gt; ' . $tmp[$customerinfo['mia']];
		$tmp = null;
	}
	else $geoloc = '<B>BRAK !! KONIECZNIE UZUPEŁNIJ !!</B>';
	$SMARTY->assign('geoloc', $geoloc);
	$SMARTY->assign('id_tariffs', $voip->get_id_tariffs());
	$SMARTY->assign('id_subscriptions', $voip->get_id_subscriptions());
	$SMARTY->assign('woj',$voip->list_woj());
	$SMARTY->assign('pow',$voip->list_pow($customerinfo['woj']));
	$SMARTY->assign('mia',$voip->list_mia($customerinfo['pow']));
}
?>
