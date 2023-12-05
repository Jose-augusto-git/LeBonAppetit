<?php

namespace Microthemer;

/*
 * TimerTrait
 *
 * measure the performance of functions
 */

trait TimerTrait {

	public $enabled = true;
	public $profiler = array();

	function getCallerFunction(){
		return debug_backtrace()[2]['function'];
	}

	function startT($fName = null){
		
		if (!$this->enabled){
			return;
		}

		$fName = $fName ?: $this->getCallerFunction();
		
		if (!isset($this->profiler[$fName])){
			$this->profiler[$fName] = array(
				'fName' => $fName,
				'sT' => [],
				'eT' => [],
				'elT' => [],
				'calls' => 0,
				'avg_time' => 0,
				'total_time' => 0
			);
		}

		$this->profiler[$fName]['sT'][++$this->profiler[$fName]['calls']] = microtime(true);
	}
	
	function endT($fName = null, $show = false, $usedInApp = false){

		if (!$this->enabled){
			return;
		}

		$fName = $fName ?: $this->getCallerFunction();

		if (!isset($this->profiler[$fName])){
			echo 'Timer not started for: ' . $fName;
			return;
		}

		// calculate time diff
		$callIndex = $this->profiler[$fName]['calls'];
		$eT = microtime(true);
		$elT = $eT - $this->profiler[$fName]['sT'][$callIndex];

		// update profiler values for single function call
		$this->profiler[$fName]['eT'][$callIndex] = $eT;
		$this->profiler[$fName]['elT'][$callIndex] = $elT;

		// update total values
		$this->profiler[$fName]['total_time']+= round($elT, 4);
		$this->profiler[$fName]['avg_time']+= round($this->profiler[$fName]['total_time'] / $callIndex, 4);

	}

	function showT(){
		return '<pre>'.print_r($this->profiler, 1).'</pre>';
	}

}