<?php

require 'bootstrap.php';

$dbConnection->exec("CREATE TABLE media(id INTEGER PRIMARY KEY, account_id INTEGER, media_name TEXT, media_storage_name TEXT, media_type TEXT, media_size INTEGER)");

print "Tabel Created";