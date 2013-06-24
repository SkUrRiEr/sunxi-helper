<?php

class memdumpBase {
	public $dram_clk;
	public $dram_type;
	public $dram_rank_num;
	public $dram_chip_density;
	public $dram_io_width;
	public $dram_bus_width;
	public $dram_cas;
	public $dram_zq;
	public $dram_odt_en;
	public $dram_tpr0;
	public $dram_tpr1;
	public $dram_tpr2;
	public $dram_tpr3;
	public $dram_emr1;
	public $dram_emr2;
	public $dram_emr3;

	/* Ordered list of the above field names, with a boolean value
	 * indicating whether they're to be dumped as hexadecimal or not.
	 */
	private $structure;

	public function __construct($data) {
		$this->structure = array(
			"dram_clk" => false,
			"dram_type" => false,
			"dram_rank_num" => false,
			"dram_chip_density" => false,
			"dram_io_width" => false,
			"dram_bus_width" => false,
			"dram_cas" => false,
			"dram_zq" => true,
			"dram_odt_en" => false,
			"dram_tpr0" => true,
			"dram_tpr1" => true,
			"dram_tpr2" => true,
			"dram_tpr3" => true,
			"dram_emr1" => true,
			"dram_emr2" => true,
			"dram_emr3" => true
		);

		foreach($data as $k => $v)
			if( property_exists($this, $k) ) {
				if( preg_match("/^0x([[:digit:]abcdef]*)$/", $v, $regs) )
					$v = hexdec($regs[1]);

				$this->$k = $v;
			}
	}

	public function getMemdump() {
		$output = "";

		foreach($this->structure as $k => $bool)
			if( $bool )
				$output .= sprintf("%-18s= 0x%0x\n", $k, $this->$k);
			else
				$output .= sprintf("%-18s= %d\n", $k, $this->$k);

		return $output;
	}
}
