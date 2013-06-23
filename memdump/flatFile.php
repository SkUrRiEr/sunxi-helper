<?php

include_once("memdump/memdumpBase.php");

class flatFile extends memdumpBase {
	public function __construct($fd) {
		$data = array();

		while($line = fgets($fd))
			if( preg_match("/^([[:alnum:]_]*)\s*=\s*([[:digit:]abcdefx]*)\s*$/", $line, $regs) )
				$data[$regs[1]] = $regs[2];

		parent::__construct($data);
	}
}
