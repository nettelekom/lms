<?php
$layout['pagetitle'] = 'Dozwolone strefy';

if($_POST['id'])
{
	$voip->wsdl->AddAllowedRates($_POST);
	$voip->wsdl->ModifyAccount(Auth::GetCurrentUser(), $_GET['account']);
	$SESSION->redirect('?m=v_nodeinfo&id=' . $_GET['account']);
}
$SMARTY->assign('sip', $_GET['account']);
$SMARTY->assign('alr', $voip->wsdl->GetAllowedRates($_GET['account']));
$voip->rategroups = $voip->wsdl->makerategroups();
$SMARTY->assign('alrs', $voip->rategroups);
$SMARTY->assign('alrss', $voip->wsdl->rategroups_selected($_GET['account']));
$SMARTY->display('v_allowedrates.html');
?>

