<?php
$v = TRUE;
$SMARTY->assign('isvoip', TRUE);
if($_POST['customerdata']) {
	$c = $_POST['customerdata'];
} else {
	$c = $voip->wsdl->GetCustomer($customerinfo, $customerid);
}
if($setl = ConfigHelper::getConfig('voip.voip_set_remb')) {
	if(date('Y-m-d', strtotime('+' . $setl . ' month')) >= $c['voipkoniecum'])
		$SMARTY->assign('setl', $setl);
}
$customersip = $voip->wsdl->GetCustomerNodes($customerid);
$customersip['ownerid'] = $customerid;
$SMARTY->assign('customersip', $customersip);
$SMARTY->assign('cdr', $voip->wsdl->GetLastUserCdr($customerid));
if($c['woj'] && $c['pow'] && $c['mia']) {
	$tmp = $voip->wsdl->list_woj();
	$geoloc = $tmp[$c['woj']];
	$tmp = $voip->wsdl->list_pow($c['woj']);
	$geoloc .= ' - &gt; ' . $tmp[$c['pow']];
	$tmp = $voip->wsdl->list_mia($c['pow']);
	$geoloc .= ' - &gt; ' . $tmp[$c['mia']];
	$tmp = null;
}
else $geoloc = '<B>BRAK !! KONIECZNIE UZUPEŁNIJ !!</B>';
$SMARTY->assign('geoloc', $geoloc);
$SMARTY->assign('id_tariffs', $voip->wsdl->get_id_tariffs());
$SMARTY->assign('id_subscriptions', $voip->wsdl->get_id_subscriptions());
$SMARTY->assign('woj',$voip->wsdl->list_woj());
$SMARTY->assign('pow',$voip->wsdl->list_pow($c['woj']));
$SMARTY->assign('mia',$voip->wsdl->list_mia($c['pow']));
$SMARTY->assign('customervoip',$c);
?>
