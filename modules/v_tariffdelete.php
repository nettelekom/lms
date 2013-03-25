<?php
if(!$voip->GetCustomersWithTariff($_GET['id']) && $_GET['is_sure'] = '1')
	$voip->TariffDelete($_GET['id']);
$SESSION->redirect('?m=v_tarifflist');
?>
