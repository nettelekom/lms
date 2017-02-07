<?php
function v_pow_mia($id, $obj, $type) {
	global $voip;
	$objResponse = new xajaxResponse();
	if($type == 'p') {
		$res = $voip->wsdl->list_pow($id);
	} elseif($type == 'm') {
		$res = $voip->wsdl->list_mia($id);
	}
	if($res) {
		$i = 0;
		$objResponse->assign($obj, 'innerHTML', '');
		if($type == 'p') {
			$objResponse->assign('mia', 'innerHTML', '');
		}
		foreach($res as $key => $val) {
			$objResponse->create($obj, 'option');
			$objResponse->assign($obj, "options[$i].text", $val);
			$objResponse->assign($obj, "options[$i].value", $key);
			$i++;
		}
	}
	return $objResponse;
	//echo "obj.options[obj.options.length] = new Option('$val','$key');";
}
$LMS->RegisterXajaxFunction('v_pow_mia');
?>
