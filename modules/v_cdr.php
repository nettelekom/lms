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
 *  $Id: invoicelist.php,v 1.34 2006/01/20 19:57:54 alec Exp $
 */

$layout['pagetitle'] = 'CDR';
$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(isset($_POST['from']))
	$from = $_POST['from'];
else
	$SESSION->restore('ilf', $from);
if(!$from) $from=date('Y/m/d',time()-86400);
$SESSION->save('ilf', $from);

if(isset($_POST['to']))
	$to = $_POST['to'];
else
	$SESSION->restore('ilt', $to);
if(!$to) $to=date('Y/m/d');
$SESSION->save('ilt', $to);

if(isset($_GET['o']))
	$o = $_GET['o'];
else
	$SESSION->restore('cdro', $o);
$SESSION->save('cdro', $o);

if(isset($_POST['customerid']))
	$c = $_POST['customerid'];
elseif(isset($_GET['customerid']))
	$c = $_GET['customerid'];
else
	$SESSION->restore('ilc', $c);
$SESSION->save('ilc', $c);

if($_POST['rategroups']) $_POST['dir']=2;
$cdr = $voip->GetCdrList($from, $to, $c, $o, $_POST['fnr'], $_POST['tnr'], $_POST['dir'], $_POST['rategroups'], $_POST['stat']);

$SESSION->restore('ilc', $listdata['customerid']);
$SESSION->restore('ilf', $listdata['from']);
$SESSION->restore('ilt', $listdata['to']);
if($_POST['csv'])
{
	$fname = tempnam("/tmp", "CSV");
	$f=fopen($fname,'w');
	fwrite($f,"Data;Z numeru;Na numer;Sekund;Klient;Strefa\n");
	foreach((array)$cdr as $key=>$val) if(is_array($val))
	{
		$line=array();
		$line[]=$val['calldate'];
		$line[]=$val['src'];
		$line[]=$val['dst'];
		$line[]=$val['seconds'];
		$line[]=$voip->toiso($val['name']);
		$line[]=$voip->toiso($val['rate']);
		fwrite($f,implode(';',$line)."\n");
	}
	fclose($f);
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="cdr.csv"');
	readfile($fname);
	unlink($fname);
	exit();

}
$listdata['order'] = $cdr['order'];
$listdata['direction'] = $cdr['direction'];
$listdata['fnr']=$_POST['fnr'];
$listdata['tnr']=$_POST['tnr'];
$listdata['dir'] = $_POST['dir'];
$listdata['stat'] = $_POST['stat'];
$listdata['rategroups'] = $_POST['rategroups'];
unset($cdr['order']);
unset($cdr['direction']);
$listdata['seconds']=$cdr['sum_seconds'];
unset($cdr['sum_seconds']);
$listdata['cost']=$cdr['sum_cost'];
unset($cdr['sum_cost']);
$listdata['tmp_cost']=$cdr['sum_tmp_cost'];
unset($cdr['sum_tmp_cost']);
$listdata['zysk']=$cdr['zysk'];
unset($cdr['zysk']);
$listdata['totalpos'] = sizeof($cdr);
$SMARTY->assign('customers', $voip->GetCustomerNames());
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('rategroups',$voip->rategroups);
$SMARTY->assign('start',$start);
$SMARTY->assign('cdr',$cdr);
$SMARTY->display('v_cdr.html');

?>
