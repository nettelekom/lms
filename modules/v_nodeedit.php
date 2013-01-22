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
 *  $Id: nodeedit.php,v 1.84 2006/01/16 09:31:57 alec Exp $
 */

if(!$voip->NodeExists($_GET['id']))
	if(isset($_GET['ownerid']))
		header('Location: ?m=customerinfo&id='.$_GET['ownerid']);
	else
		header('Location: ?m=v_nodelist');

$action = isset($_GET['action']) ? $_GET['action'] : '';

$nodeid = $_GET['id'];
$customerid = $voip->GetNodeOwner($nodeid);
$SESSION->save('backto', $_SERVER['QUERY_STRING']);
	
if(!isset($_GET['ownerid']))
	$SESSION->save('backto', $SESSION->get('backto') . '&ownerid='.$customerid);
							
$layout['pagetitle'] = 'Edycja konta';

include(MODULES_DIR . '/customer.inc.php');
include(MODULES_DIR.'/customer.voip.inc.php');

$nodeinfo = $voip->GetNode($_GET['id']);
$nodeinfo['ownerid']=$customerid;
$nodeinfo['id']=$nodeinfo['id_ast_sip'];

if(isset($_POST['nodedata']))
{
	$nodeedit = $_POST['nodedata'];
	foreach($nodeedit as $key => $value)
		$nodeedit[$key] = trim($value);
	
	if($nodeedit['secret']=='')
	{
		$SESSION->redirect('?m=v_nodeinfo&id='.$nodeedit['id']);
	}
	$nodeedit['finlimit']=str_replace(',','.',$nodeedit['finlimit']);
	if(!preg_match('/^[0-9.]+$/',$nodeedit['finlimit']))
		$error['finlimit'] = 'Niedozwolone znaki!';
	elseif(!preg_match('/^[0-9.]+$/',$nodeedit['afinlimit']))
		$error['afinlimit'] = 'Niedozwolone znaki!';

	if(strlen($nodeedit['secret'])>32)
		$error['secret'] = trans('Password is too long (max.32 characters)!');
	if($nodeedit['voicemailaddr'] && !check_email($nodeedit['voicemailaddr']))
		$error['voicemailaddr'] = 'Błędny adres email!';
	if($nodeedit['faxmailaddr'] && !check_email($nodeedit['faxmailaddr']))
		$error['faxmailaddr'] = 'Błędny adres email!';
	if($nodeedit['sippostcode'] && !preg_match('/^\d{2}-\d{3}$/', $nodeedit['sippostcode']))
		$error['sippostcode'] = 'Błędny wpis!';

	if(!preg_match('/^[0-9.,\/]+$/',$nodeedit['permit'])) $error['permit']='Błędny wpis!';
		else
		{
			$tmp=explode(',',$nodeedit['permit']);
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
				if(count($toadd)==1) $nodeedit['permit']=$toadd[0];
					else if(!empty($toadd)) $nodeedit['permit']=implode(';',$toadd);
			}
		}

	if(!$error)
	{
		$nodeedit['modifierid'] = $AUTH->id;
		$voip->NodeUpdate($nodeedit);
		$SESSION->redirect('?m=v_nodeinfo&id='.$nodeedit['id']);
	}
	else
		$SMARTY->assign('nodedata',$nodeedit);

}
else
	$SMARTY->assign('nodedata',$nodeinfo);

if($customerinfo['status']==3) $customerinfo['shownodes'] = TRUE;
$customers = $voip->GetCustomerNames();

$SMARTY->assign('busy_action',array('busy'=>'Sygnał zajętości','voicemail'=>'Poczta głosowa','forward'=>'Przekieruj'));
$SMARTY->assign('unavail_action',array('unavail'=>'Sygnał niedostępności','voicemail'=>'Poczta głosowa','forward'=>'Przekieruj'));
$SMARTY->assign('yesno',array('no'=>'Nie','yes'=>'Tak'));
$SMARTY->assign('dtmfmode',array('rfc2833'=>'rfc2833','inband'=>'inband','info'=>'info','auto'=>'auto'));
$SMARTY->assign('nat',array('yes'=>'Tak','no'=>'Nie','never'=>'Nigdy','route'=>'Route'));
$SMARTY->assign('trunks_allowed',$voip->get_trunks_allowed());
$SMARTY->assign('error',$error);
$SMARTY->assign('customers',$customers);
$SMARTY->display('v_nodeedit.html');

?>
