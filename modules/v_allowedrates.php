<?php
$voip->rategroups=$voip->makerategroups();
$layout['pagetitle'] = 'Dozwolone strefy';

if($_POST['id'])
{
$voip->AddAllowedRates($_POST);
$voip->ModifyAccount($AUTH->id, $_GET['account']);
$SESSION->redirect('?m=v_nodeinfo&id='.$_GET['account']);
}
$SMARTY->assign('sip',$_GET['account']);
$SMARTY->assign('alr',$voip->GetAllowedRates($_GET['account']));
$SMARTY->assign('alrs',$voip->rategroups);
$SMARTY->assign('alrss',$voip->rategroups_selected($_GET['account']));
$SMARTY->display('v_allowedrates.html');
?>

