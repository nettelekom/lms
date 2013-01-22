<?php
if(count($_POST['marks'])>0)
foreach($_POST['marks'] as $val)
{
	if($_POST['original']) echo $voip->Invoice($val);
	if($_POST['copy']) echo $voip->Invoice($val,'&copy=1');
	if($_POST['dupldate'] && $_GET['duplicate'])
	{
		list($data,$null)=explode(' ',$_POST['dupldate']);
		echo $voip->Invoice($val,'&dupldate='.str_replace('/','-',$data));
	}
}
elseif($_GET['id'])
echo $voip->Invoice($_GET['id']);
else
	$SESSION->redirect('?m=v_invoicelist');
exit();
?>
