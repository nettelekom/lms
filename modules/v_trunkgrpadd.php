<?php
$layout['pagetitle'] = 'Nowa grupa cennikowa';
$ca=$_POST['cennadd'];
if(isset($ca))
{
	$ca['name']=trim($ca['name']);
	if($ca['name']=='')
		$error['name'] = 'Nazwa grupy jest wymagana !';
	if($voip->TrunkgrpExists($ca['name']))
		$error['name'] = 'Taka grupa już istnieje !';
if(!$error) $SESSION->redirect('?m=v_trunkgrpinfo&id='.$voip->TrunkgrpAdd($ca['name']));

}
$SMARTY->assign('error',$error);
$SMARTY->assign('cennadd',$ca);
$SMARTY->display('v_trunkgrpadd.html');
?>
