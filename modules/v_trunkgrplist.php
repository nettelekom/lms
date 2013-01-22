<?php
$SMARTY->assign('trunkgrplist',$voip->GetTrunkgrpList());

$layout['pagetitle'] = 'VOIP - lista grup cennikowych';
$SMARTY->display('v_trunkgrplist.html');
?>
