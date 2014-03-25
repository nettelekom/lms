<?php
$s = $voip->wsdl->GetSettings();
$n = $voip->wsdl->GetNode($_GET['account']);
$u = $voip->GetUserToSettings($voip->wsdl->GetNodeOwner($_GET['account']), $s[3]);
//$layout['pagetitle'] = 'Ustawienia konta ' . $n['name'];
$m = array();
$m[name] = $u['lastname'].' '.$u['name']; // - ustawienia konta ' . $n['name'];
$m[sip][addr] = $s[1];
$m[sip][login] = $n['accountcode'];
$m[sip][passwd] = $n['secret'];
$m[voicemail][no] = $s[4];
$m[voicemail][login] = $n['mailbox'];
$m[voicemail][passwd] = $n['mailboxpin'];
$m[ibok][addr] = $s[2];
$m[ibok][login] = $u['login'];
$m[ibok][passwd] = $u['pin'];
$SMARTY->assign('db', $m);
$SMARTY->display('v_print.html');
?>

