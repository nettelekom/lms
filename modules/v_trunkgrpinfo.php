<?php
$layout['pagetitle'] = $voip->GetTrunkgrpName($_GET['id']);
$t=$voip->GetTrunkHours($_GET['id']);
$SMARTY->assign('t',$t);
$SMARTY->display('v_trunkgrpinfo.html');

?>
