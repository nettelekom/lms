<?php
$voip->TrunkgrpDelete($_GET['id']);
$SESSION->redirect('?m=v_trunkgrplist');
?>
