<?php
$ownerid = isset($_GET['ownerid']) ? $_GET['ownerid'] : 0;
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if($voip->CustomerExists($ownerid))
{
    $voip->wsdl->NodeSetU($ownerid, $_GET['access'], Auth::GetCurrentUser());

	$backid = $ownerid;
	$redir = $SESSION->get('backto');
	if($SESSION->get('lastmodule') == 'customersearch')
		$redir .= '&search=1';

	$SESSION->redirect('?' . $redir . '#' . $backid);
}

if($voip->wsdl->NodeExists($id))
{
    $voip->wsdl->NodeSet($id, Auth::GetCurrentUser());
	$backid = $id;
}

header('Location: ?' . $SESSION->get('backto') . '#' . $backid);

?>
