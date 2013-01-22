<?php

$SMARTY->assign('tarifflist',$voip->get_tariffs());
$SMARTY->assign('listdata',$voip->temp);


$layout['pagetitle'] = 'VOIP - telefonia internetowa';
$SMARTY->display('v_tarifflist.html');
?>
