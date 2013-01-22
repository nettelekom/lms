<?php
$api=new floAPI($CONFIG['phpui']['voip_as_login'], $CONFIG['phpui']['voip_as_pass'], $CONFIG['phpui']['voip_as_host']);
$api->request('COMMAND', array('COMMAND' => 'core restart now'),false);
sleep(5);
$SESSION->redirect('?m=v_state');
?>
