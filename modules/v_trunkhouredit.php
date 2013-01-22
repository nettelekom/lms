<?php
if($_GET['id_rates'])
{
	$rate=$voip->getratebyid($_GET['id_rates']);
	$layout['pagetitle'] = $voip->GetTrunkgrpName($_GET['id']).' - '.$rate[0]['desc'].' - edytuj godziny';
}
$layout['pagetitle'] = $voip->GetTrunkgrpName($_GET['id']).' - '.$voip->cnames[$_GET['c']].' - edytuj godziny';

$ha=$_POST['hoursadd'];
if(isset($ha))
{
	foreach($ha as $key=>$val)
		$ha[$key]=trim($val);
	$ha['price']=str_replace(',','.',$ha['price']);
$days=$_POST['days'];
$ids=$_POST['ids'];
if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/',$ha['from']))
	$error['from']='Błędna godzina !';
if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/',$ha['to']))
	$error['to']='Błędna godzina !';
if(!preg_match('/\d+/',$ha['price']))
	$error['price']='Błędna kwota !';

if(!$error) 
{
	$voip->TrunkEditHours($ha,$days,$_GET['c'],$ids);
	$SESSION->redirect('?m=v_trunkhours&c='.$_GET['c'].'&id='.$_GET['id'].'&id_rates='.$_GET['id_rates']);
}

}
else
{
	if($_GET['id_rates'])
list($ha,$days,$ids)=$voip->GetTrunkHoursToEdit2($_GET['id'],$_GET['id_rates'],$_GET['count']);
else
list($ha,$days,$ids)=$voip->GetTrunkHoursToEdit($_GET['id'],$_GET['c'],$_GET['count']);
}
	$SMARTY->assign('hoursadd',$ha);
	$SMARTY->assign('days',$days);
	$SMARTY->assign('ids',$ids);
$SMARTY->assign('error',$error);
$SMARTY->assign('listdata',array('c'=>$_GET['c'],'id'=>$_GET['id']));
$SMARTY->display('v_trunkhouredit.html');
?>
