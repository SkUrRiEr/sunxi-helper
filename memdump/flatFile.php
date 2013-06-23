<?php

include_once("memdump/memdumpBase.php");

class flatFile extends memdumpBase {
	public function __construct($fd) {
		while($line = fgets($fd))
			if( preg_match("/^([[:alnum:]_]*)\s*=\s*([[:digit:]abcdefx]*)$/", $line, $regs) ) {
				$x = $regs[1];

				if( property_exists($this, $x) )
					$this->$x = $regs[2];
			}
	}
}
