<?php
if($_GET['id']) $voip->ratedel($_GET['id']);
$SESSION->redirect('?m=v_numbers');
?>
