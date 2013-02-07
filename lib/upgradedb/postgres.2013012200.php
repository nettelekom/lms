<?php

/*
 * LMS version 1.11-git
 */

$DB->BeginTrans();

$DB->Execute('ALTER TABLE v_fax RENAME COLUMN "user" TO "customerid" ;');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013012200', 'dbversion'));

$DB->CommitTrans();

?>
