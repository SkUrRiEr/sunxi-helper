<?php

function usage($e) {
	$f = fopen("php://stderr", "w");

	fputs($f, $_SERVER["argv"][0]." <memdump>...\n\nIf you specify a template (*.tpl) on the command line, it will be used to\noutput the memdump.\n\n".$e."\n\n");

	exit(1);
}

if( $_SERVER["argc"] < 2 )
	usage("Incorrect arguments specified");

if( !file_exists($_SERVER["argv"][1]) )
	usage("Cannot find file specified");

require_once("memdump/flatFile.php");
require_once("memdump/cFile.php");

$dumps = array();
$names = array();

foreach($_SERVER["argv"] as $i => $f) {
	if( $i == 0 )
		continue;

	$fd = fopen($f, "r");

	if( !$fd )
		echo "Cannot open ".$f."\n";

	if( preg_match("/\.tpl$/", $f) )
		$tpl = $fd;
	else {
		$names[] = $f;

		$line = fgets($fd);
		fseek($fd, 0);

		if( preg_match("/^dram_/", $line) )
			$dumps[] = new flatFile($fd);
		else
			$dumps[] = new cFile($fd);
	}
}

if( count($dumps) == 0 )
	usage("No memdumps specified.");

if( count($dumps) > 1 ) {
	echo "Comparison Matrix:\n";

	$ll = 0;

	foreach($names as $n)
		if( strlen($n) > $ll )
			$ll = strlen($n);

	echo sprintf("%-".$ll."s | ", "").implode(array_keys($names), " | ")." |\n";

	foreach($dumps as $i => $x) {
		$cmp = array();

		foreach($dumps as $y)
			$cmp[] = $x->equal($y) ? "x" : " ";

		echo sprintf("%-".$ll."s | ", $names[$i]).implode($cmp, " | ")." |\n";
	}

	echo "\nFirst parsed file:\n";
}

if( isset($tpl) )
	echo $dumps[0]->getByTemplate($tpl);
else
	echo $dumps[0]->getMemdump();
