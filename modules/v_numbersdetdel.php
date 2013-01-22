<?php
if($_GET['id']) $voip->deletenumber($_GET['id']);
$SESSION->redirect('?m=v_numbersdet&id='.$_GET['id_rates']);
?>
