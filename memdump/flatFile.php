<?php

include_once("memdump/memdumpBase.php");

class flatFile extends memdumpBase {
	public function __construct($fd) {
		while($line = fgets($fd))
			if( preg_match("/^([[:alnum:]_]*)\s*=\s*([[:digit:]abcdefx]*)\s*$/", $line, $regs) ) {
				$k = $regs[1];

				$v = $regs[2];

				if( preg_match("/^0x([[:digit:]abcdef]*)$/", $v, $regs) )
					$v = hexdec($regs[1]);

				if( property_exists($this, $k) )
					$this->$k = $v;
			}
	}
}
