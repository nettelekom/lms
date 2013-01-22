<?php

/*
 * LMS version 1.9.1 Jumar
 *
 *  (C) Copyright 2001-2006 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id: reload.php,v 1.40 2006/01/16 09:31:58 alec Exp $
 */
$query=$voip->GetState();
$SMARTY->display('header.html');
echo '<H1>Połączenia</H1>';
echo '<TABLE WIDTH="100%" CLASS="superlight" CELLPADDING="5"><TR><TD CLASS="FALL">';
echo '<PRE>'.$query['channels'].'</PRE>';
echo '</TD></TR></TABLE><BR><BR>';
echo '<H1>Klienci</H1>';
echo '<TABLE WIDTH="100%" CLASS="superlight" CELLPADDING="5"><TR><TD CLASS="FALL">';
$sip=$voip->GetAccountsForState();
$cl=explode("\n",$query['clients']);
echo '<PRE>';
echo "$cl[0]\n$cl[1]\n$cl[2]\n";
unset($cl[0]);
unset($cl[1]);
unset($cl[2]);
foreach($sip as $val)
{
$out='';
foreach($cl as $key=>$val1)
if(is_array($val['accountcode']) && in_array(substr($val1,0,10),$val['accountcode']))
{
$out.=$val1."\n";
unset($cl[$key]);
}
if($out) echo "\n<b>".$val['surname'].' '.$val['forename']."</b>\n$out";
reset($cl);
}
echo "\n\n".implode("\n",$cl);

echo '</PRE>';
echo '</TD></TR></TABLE>';
echo '<table><tr><td><a href="?m=v_reload" onclick="return confirm(\'Uwaga! Wszystkie bieżące połączenia zostaną zakończone. Kontynuować ?\');">Przeładuj centralę</a></td></tr></table>';
$SMARTY->display('footer.html');
/*$res=$DB->GetAll('select id,name from billing_details where documents_id=1301');
foreach($res as $val) 
{
$na=preg_replace('/Po(.+)czenia/','Połączenia',$val['name']);
$na=preg_replace('/kom(.+)kowe/','komórkowe',$na);
echo $voip->toiso($na);
$DB->Execute('update billing_details set name="'.$na.'" where id=?',array($val['id']));
var_dump($DB);
}*/
?>
