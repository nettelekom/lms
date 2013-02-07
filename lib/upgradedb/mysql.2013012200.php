<?php

/*
 * LMS version 1.11-git
 */

$DB->BeginTrans();

$DB->Execute("ALTER TABLE `v_fax` CHANGE `user` `customerid` INT( 10 ) UNSIGNED NOT NULL");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013012200', 'dbversion'));

$DB->CommitTrans();

?>
