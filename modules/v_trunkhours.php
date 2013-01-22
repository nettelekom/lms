<?php
$voip->rategroups=$voip->makerategroups();
if($_GET['id_rates'])
{
	$rate=$voip->getratebyid($_GET['id_rates']);
	$layout['pagetitle'] = $voip->GetTrunkgrpName($_GET['id']).' - '.$rate[0]['desc'];
	$hours=$voip->GetTrunkHourDetails2($_GET['id'],$_GET['id_rates']);
}
else
{
	$layout['pagetitle'] = $voip->GetTrunkgrpName($_GET['id']).' - '.$voip->rategroups[$_GET['c']];
	$hours=$voip->GetTrunkHourDetails($_GET['id'],$_GET['c']);
}
$SMARTY->assign('hours',$hours);
$SMARTY->assign('listdata',$_GET);
$SMARTY->display('v_trunkhours.html');
?>
