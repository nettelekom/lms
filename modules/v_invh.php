<?php
if($_GET['id'] && $_GET['od']>0 && $_GET['od']<date('t'))
$voip->InvH($_GET['id'],$_GET['od']);
$SESSION->redirect('?m=customerinfo&id='.$_GET['id']);
?>
