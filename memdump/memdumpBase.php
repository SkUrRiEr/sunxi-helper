<?php

class memdumpBase {
	public $clk;
	public $type;
	public $rank_num;
	public $chip_density;
	public $io_width;
	public $bus_width;
	public $cas;
	public $zq;
	public $odt_en;
	public $size;
	public $tpr0;
	public $tpr1;
	public $tpr2;
	public $tpr3;
	public $tpr4;
	public $tpr5;
	public $emr1;
	public $emr2;
	public $emr3;

	/* Ordered list of the above field names, with a boolean value
	 * indicating whether they're to be dumped as hexadecimal or not.
	 */
	private $structure;

	public function __construct($data) {
		$this->structure = array(
			"clk" => false,
			"type" => false,
			"rank_num" => false,
			"chip_density" => false,
			"io_width" => false,
			"bus_width" => false,
			"cas" => false,
			"zq" => true,
			"odt_en" => false,
			"size" => false,
			"tpr0" => true,
			"tpr1" => true,
			"tpr2" => true,
			"tpr3" => true,
			"tpr4" => true,
			"tpr5" => true,
			"emr1" => true,
			"emr2" => true,
			"emr3" => true
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
				$output .= sprintf("%-18s= 0x%0x\n", "dram_".$k, $this->$k);
			else
				$output .= sprintf("%-18s= %d\n", "dram_".$k, $this->$k);

		return $output;
	}

	public function equal(memdumpBase $x) {
		foreach($this->structure as $k => $bool)
			if( isset($this->$k) != isset($x->$k) || $this->$k != $x->$k )
				return false;

		return true;
	}
}
