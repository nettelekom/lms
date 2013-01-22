<?php
$voip->rategroups=$voip->makerategroups();
if($_POST['cennfrom'] && $_POST['act']=='Skopiuj') $voip->MoveCenn($_POST['cennfrom'],$_GET['id'],$_POST['grupa1']);
elseif($_POST['co'] && $_POST['cennchange'] && substr($_POST['act'],0,4)=='Zmie') $voip->CennChange($_GET['id'],$_POST['cennchange'],$_POST['co'],$_POST['grupa']);
$layout['pagetitle'] = $voip->GetCennName($_GET['id']);
$t=$voip->GetHours($_GET['id']);
$SMARTY->assign('c',$voip->cnames);
$SMARTY->assign('t',$t);
$cennlist=$voip->get_cenn();
$trunks=$voip->GetTrunkgrpList();
$out=array();
foreach($cennlist as $val) if($val['id']!=$_GET['id']) $out[$val['id']]=$val['name'];
foreach($trunks as $val) $out['t_'.$val['id']]=$val['name'];
$SMARTY->assign('cennfrom',$out);
$SMARTY->assign('rategr',$voip->rategroups);
$cust=$voip->GetCustomersWithT($_GET['id']);
$SMARTY->assign('tariff',$cust);
$SMARTY->display('v_cenninfo.html');

?>
