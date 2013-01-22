<?php
$users=$voip->GetCustomerNames();
foreach($users as $us)
{
	$voip->UpdateCustomerBalance($us['id'],-$LMS->GetCustomerBalance($us['id']));
}
$voip->UpdateTax($LMS->GetTaxes());
$SESSION->redirect('?'.$SESSION->get('backto'));
?>
