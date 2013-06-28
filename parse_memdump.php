<?php

function usage($e) {
	$f = fopen("php://stderr", "w");

	fputs($f, $_SERVER["argv"][0]." <memdump>\n\n".$e."\n\n");

	exit(1);
}

if( $_SERVER["argc"] != 2 )
	usage("Incorrect arguments specified");

if( !file_exists($_SERVER["argv"][1]) )
	usage("Cannot find file specified");

$f = fopen($_SERVER["argv"][1], "r");

if( !$f )
	usage("Cannot open specified file");

require_once("memdump/flatFile.php");
require_once("memdump/cFile.php");

$line = fgets($f);
fseek($f, 0);

if( preg_match("/^dram_/", $line) )
	$memdump = new flatFile($f);
else
	$memdump = new cFile($f);

echo $memdump->getMemdump();
