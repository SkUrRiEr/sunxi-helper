<?php

include_once("memdump/memdumpBase.php");

class cFile extends memdumpBase {
	private $mapping;

	public function __construct($fd) {
		$this->mapping = array(
			"clock" => "clk",
			"type" => "type",
			"rank_num" => "rank_num",
			"density" => "chip_density",
			"io_width" => "io_width",
			"bus_width" => "bus_width",
			"cas" => "cas",
			"zq" => "zq",
			"odt_en" => "odt_en",
			"size" => "size",
			"tpr0" => "tpr0",
			"tpr1" => "tpr1",
			"tpr2" => "tpr2",
			"tpr3" => "tpr3",
			"tpr4" => "tpr4",
			"tpr5" => "tpr5",
			"emr1" => "emr1",
			"emr2" => "emr2",
			"emr3" => "emr3"
		);

		$data = array();

		while(($line = fgets($fd)) && !preg_match("/struct\s+dram_para.*?(=\s*\{)?\s*$/", $line, $regs))
			/* Wait */;

		if(!$line)
			throw new Exception("Cannot find \"struct dram_para\" definition in provided file.");

		if( !isset($regs[1]) )
			while(($line = fgets($fd)) && !preg_match("/=\s*\{/", $line, $regs))
				/* Wait */;

		if(!$line)
			throw new Exception("Cannot find start of \"struct dram_para\" definition in provided file.");

		while($line = fgets($fd)) {
			if(preg_match("/^\s*\.([[:alnum:]_]+)\s*=\s*([[:digit:]abcdefx]+)\s*(,\s*)?$/", $line, $regs) && isset($this->mapping[$regs[1]]))
				$data[$this->mapping[$regs[1]]] = $regs[2];
			
			if(preg_match("/}\s*;/", $line))
				break;
		}

		parent::__construct($data);
	}
}
