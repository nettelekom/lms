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
 *  $Id: nodeadd.php,v 1.67 2006/01/16 09:31:57 alec Exp $
 */

$nodedata = $_POST['nodedata'];

if(isset($nodedata))
{
	foreach($nodedata as $key => $value)
		$nodedata[$key] = trim($value);

	if($nodedata['secret']=='' && $nodedata['name']=='')
		if($_GET['ownerid'])
		{
			$SESSION->redirect('?m=customerinfo&id='.$_GET['ownerid']);
		}else{
			$SESSION->redirect('?m=nodelist');
		}
	
	if($nodedata['name']=='')
		$error['name'] = trans('Node name is required!');
	elseif(strlen($nodedata['name']) > 16)
		$error['name'] = trans('Node name is too long (max.16 characters)!');
	elseif($voip->GetNodeIDByName($nodedata['name']))
		$error['name'] = trans('Specified name is in use!');
	elseif(!preg_match('/^[_a-z0-9-]+$/',$nodedata['name']))
		$error['name'] = trans('Specified name contains forbidden characters!');		

	
	if(strlen($nodedata['secret']) > 32)
		$error['secret'] = trans('Password is too long (max.32 characters)!');

	if(! $LMS->CustomerExists($nodedata['ownerid']))
		$error['customer'] = trans('You have to select owner!');
	elseif($LMS->GetCustomerStatus($nodedata['ownerid']) != 3)
		$error['customer'] = trans('Selected customer is not connected!');
	if(!$nodedata['id_tariffs']) $error['id_tariffs'] = 'Musisz wybrać taryfę!';
	if($nodedata['sippostcode'] && !preg_match('/^\d{2}-\d{3}$/', $nodedata['sippostcode']))
		$error['sippostcode'] = 'Błędny wpis!';

	if(!preg_match('/^[0-9.,\/]+$/',$nodedata['permit'])) $error['permit']='Błędny wpis!';
		else
		{
			$tmp=explode(',',$nodedata['permit']);
			if(count($tmp)>3) $error['permit']='Zbyt duża ilość wpisów!';
			else
			{
				$toadd=array();
				foreach($tmp as $val)
				{
					$val=trim($val);
					if(strpos($val,'/')===FALSE)
					{
						if(!check_ip($val)) $error['permit']='Błędny adres IP';
						else $toadd[]=$val;
					}
					else
					{
						$tmp2=explode('/',$val);
						$netaddr=getnetaddr($tmp2[0],prefix2mask($tmp2[1]));
						if(!$netaddr || $tmp2[1]>32 || $tmp2[1]<8) $error['permit']='Błędny adres IP';
						else $toadd[]=$netaddr.'/'.$tmp2[1];
					}
				}
				if(count($toadd)==1) $nodedata['permit']=$toadd[0];
					else if(!empty($toadd)) $nodedata['permit']=implode(';',$toadd);
			}
		}

	if(!$error)
	{
		$nodedata['creatorid'] = $AUTH->id;
		$nodeid = $voip->NodeAdd($nodedata);
		$voip->AddFreeSec($nodedata['ownerid'],$nodedata['id_subscriptions']);
		if(!isset($nodedata['reuse']))
		{
			$SESSION->redirect('?m=v_nodeinfo&id='.$nodeid);
		}
		unset($nodedata);
		$nodedata['reuse'] = '1';
	}
	
}

if($LMS->CustomerExists($_GET['ownerid']) < 0)
{
	$SESSION->redirect('?m=customerinfo&id='.$_GET['ownerid']);
}

$nodedata['access'] = 1;

if($_GET['ownerid'] && $LMS->CustomerExists($_GET['ownerid']) > 0)
{
	$nodedata['ownerid'] = $_GET['ownerid'];
	$customerid = $_GET['ownerid'];
	include(MODULES_DIR . '/customer.inc.php');
	include(MODULES_DIR.'/customer.voip.inc.php');
}

if(isset($_GET['preip']) && $nodedata['name']=='')
	$nodedata['name'] = $_GET['preip'];

$layout['pagetitle'] = trans('Nowe konto SIP');

$customers = $voip->GetCustomerNames();

$SMARTY->assign('busy_action',array('busy'=>'Sygnał zajętości','voicemail'=>'Poczta głosowa','forward'=>'Przekieruj'));
$SMARTY->assign('unavail_action',array('unavail'=>'Sygnał niedostępności','voicemail'=>'Poczta głosowa','forward'=>'Przekieruj'));
$SMARTY->assign('yesno',array('no'=>'Nie','yes'=>'Tak'));
$SMARTY->assign('dtmfmode',array('rfc2833'=>'rfc2833','inband'=>'inband','info'=>'info','auto'=>'auto'));
$SMARTY->assign('nat',array('yes'=>'Tak','no'=>'Nie','never'=>'Nigdy','route'=>'Route'));
$SMARTY->assign('trunks_allowed',$voip->get_trunks_allowed());
$SMARTY->assign('id_tariffs',$voip->get_id_tariffs());
$SMARTY->assign('id_subscriptions',$voip->get_id_subscriptions());
$SMARTY->assign('customers',$customers);
$SMARTY->assign('error',$error);
$SMARTY->assign('nodedata',$nodedata);
$SMARTY->display('v_nodeadd.html');

?>
