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
 *  $Id: invoiceedit.php,v 1.18 2006/02/05 16:48:04 alec Exp $
 */

$customers = $voip->GetCustomerNames();
$tariffs = $voip->Get_Tariffs();

if($_GET['action']=='invoicezero' && isset($_GET['id']))
{
	$voip->InvoiceZero($_GET['id']);
	$SESSION->redirect('?m=customerinfo&id='.$cid);
}

//var_dump($SESSION);
if ((isset($_GET['id'])) && ($_GET['action']=='edit'))
{
    $invoice = $voip->GetInvoiceContent($_GET['id']);
    $invoice['number']=$invoice['bno_prefix'].' '.$invoice['bno_no'].'/'.$invoice['bno_month'].'/'.$invoice['bno_year'];
    $SESSION->remove('invoicecontents');
    $SESSION->remove('invoicecustomer');

    foreach ($invoice['content'] as $item) {
	$i++;
//        $nitem['tariffid']	= $item['tariffid'];
	$nitem['name']		= $item['name'];
//	$nitem['prodid']	= $item['prodid'];
        $nitem['count']		= str_replace(',','.',$item['number']);
//	$nitem['discount']	= str_replace(',','.',$item['discount']);
//	$nitem['jm']		= str_replace(',','.',$item['content']);
        $nitem['valuenetto']	= str_replace(',','.',$item['price_for_one']);
        $nitem['valuebrutto']	= str_replace(',','.',$item['price_for_one']*1.22);
	$nitem['s_valuenetto']	= str_replace(',','.',$item['number']*$item['price_for_one']);
	$nitem['s_valuebrutto']	= str_replace(',','.',$item['price_for_all']);
//	$nitem['tax']		= $taxeslist[$item['taxid']]['label'];
//	$nitem['taxid']		= $item['taxid'];
	$nitem['posuid']	= $i;
	$SESSION->restore('invoicecontents', $invoicecontents);
	$invoicecontents[] = $nitem;
	$SESSION->save('invoicecontents', $invoicecontents);
    }
    $SESSION->save('invoicecustomer', $LMS->GetCustomer($invoice['customerid']));
    $invoice['oldcdate'] = $invoice['cdate'];
    $SESSION->save('invoice', $invoice);
    $SESSION->save('invoiceid', $invoice['id']);

}

$SESSION->restore('invoicecontents', $contents);
$SESSION->restore('invoicecustomer', $customer);
$SESSION->restore('invoice', $invoice);
$SESSION->restore('invoiceediterror', $error);

$itemdata = r_trim($_POST);

//$ntempl = docnumber($invoice['number'], $invoice['template'], $invoice['cdate']);
$layout['pagetitle'] = trans('Invoice Edit: $0', $invoice['number']);

if($_GET['customerid'] != '' && $LMS->CustomerExists($_GET['customerid']))
	$_GET['action'] = 'setcustomer';

switch($_GET['action'])
{
	case 'additem':
		$itemdata = r_trim($_POST);
		foreach(array('count', 'discount', 'valuenetto', 'valuebrutto') as $key)
			$itemdata[$key] = round((float) str_replace(',','.',$itemdata[$key]),2);
		if($itemdata['count'] > 0 && $itemdata['name'] != '')
		{
			$taxvalue = 22;
			if($itemdata['valuenetto'] != 0)
			{
			    	$itemdata['valuenetto'] = f_round($itemdata['valuenetto'] - $itemdata['valuenetto'] * f_round($itemdata['discount'])/100);
				$itemdata['valuebrutto'] = round($itemdata['valuenetto'] * ($taxvalue / 100 + 1),2);
			}
			elseif($itemdata['valuebrutto'] != 0)
			{
			    	$itemdata['valuebrutto'] = f_round($itemdata['valuebrutto'] - $itemdata['valuebrutto'] * f_round($itemdata['discount'])/100);
				$itemdata['valuenetto'] = round($itemdata['valuebrutto'] / ($taxvalue + 100) * 100, 2);
			}
			
			// str_replace here is needed because of bug in some PHP versions (4.3.10)
			$itemdata['s_valuenetto'] = str_replace(',','.',$itemdata['valuenetto'] * $itemdata['count']);
			$itemdata['s_valuebrutto'] = str_replace(',','.',$itemdata['valuebrutto'] * $itemdata['count']);
			$itemdata['valuenetto'] = str_replace(',','.',$itemdata['valuenetto']);
			$itemdata['valuebrutto'] = str_replace(',','.',$itemdata['valuebrutto']);
			$itemdata['count'] = str_replace(',','.',$itemdata['count']);
			$itemdata['discount'] = str_replace(',','.',$itemdata['discount']);
			$itemdata['tax'] = $taxeslist[$itemdata['taxid']]['label'];
			$itemdata['posuid'] = (string) getmicrotime();
			$contents[] = $itemdata;
		}
	break;

	case 'deletepos':
		if(sizeof($contents))
			foreach($contents as $idx => $row)
				if($row['posuid'] == $_GET['posuid']) 
					unset($contents[$idx]);
	break;

	case 'setcustomer':
		
		$olddate = $invoice['oldcdate'];
		
		unset($invoice); 
		unset($customer);
		unset($error);
		
		if($invoice = $_POST['invoice'])
			foreach($invoice as $key => $val)
				$invoice[$key] = $val;
		
		$invoice['paytime'] = sprintf('%d', $invoice['paytime']);
		$invoice['oldcdate'] = $olddate;
		
		if($invoice['paytime'] < 0)
			$invoice['paytime'] = 14;

		if($invoice['cdate']) // && !$invoice['cdatewarning'])
		{
			list($year, $month, $day) = split('/',$invoice['cdate']);
			if(checkdate($month, $day, $year))
			{
				//$invoice['cdate'] = mktime(date('G',time()),date('i',time()),date('s',time()),$month,$day,$year);
			}
			else
				$error['cdate'] = trans('Incorrect date format!');
		}
		
		$invoice['customerid'] = $_POST['customerid'];
		
		if(!$error)
			if($LMS->CustomerExists(($_GET['customerid'] != '' ? $_GET['customerid'] : $_POST['customer'])))
				$customer = $LMS->GetCustomer(($_GET['customerid'] != '' ? $_GET['customerid'] : $_POST['customer']));
	break;

	case 'save':

		if($contents && $customer)
		{
			$SESSION->restore('invoiceid', $invoice['id']);
			$invoice['type'] = DOC_INVOICE;
			$voip->InvoiceUpdate(array('customer' => $customer, 'contents' => $contents, 'invoice' => $invoice));
			$SESSION->redirect('?m=v_invoice&id='.$invoice['id']);
		}
	break;

	case 'invoicedel':
	    $voip->InvoiceDelete($_GET['id']);
	    $SESSION->redirect('?m=v_invoicelist');
}

if($invoice['paytype'] == '')
	$invoice['paytype'] = trans('CASH');

$SESSION->save('invoice', $invoice);
$SESSION->save('invoicecontents', $contents);
$SESSION->save('invoicecustomer', $customer);
$SESSION->save('invoiceediterror', $error);

if($_GET['action'] != '')
{
	// redirect, ¿eby refreshem nie spierdoliæ faktury
	$SESSION->redirect('?m=v_invoiceedit');
}
//var_dump($invoice);
$SMARTY->assign('error', $error);
$SMARTY->assign('contents', $contents);
$SMARTY->assign('customer', $customer);
$SMARTY->assign('invoice', $invoice);
$SMARTY->assign('tariffs', $tariffs);
$SMARTY->assign('customers', $customers);
$SMARTY->assign('taxeslist', $taxeslist);
$SMARTY->display('v_invoiceedit.html');

?>
