<?php
$SMARTY->assign('tarifflist',$voip->get_cenn());

$layout['pagetitle'] = 'VOIP - lista cenników';
$SMARTY->display('v_cennlist.html');
?>
