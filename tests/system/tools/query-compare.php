<?php
require_once dirname(__FILE__) . '/../config/config.php';

class LogCompare {

	protected $_config;
	public $files = array();
	
	public function __construct()
	{
	    $this->_config = new Tests_System_Config;
		$this->loadLogs();
	}

	public function loadLogs()
	{
		$log_dir = $this->_config->queryLogPath;
		$files = scandir($log_dir);
		foreach ($files as $k => $file) {
			if (strrchr($file, '.') !== '.log' || $file == '.' || $file == '..') {
			    unset($files[$k]);
			}
		}
		
		$this->files = array_values($files);
	}
	
	public function compareLogs($base_driver, $compared_driver, $test = null)
	{
		$files = array($base_driver => array(), $compared_driver => array());
		// organize log files
		foreach($this->files as $file) {
			if (stripos($file, $base_driver) === 0) {
				$files[$base_driver][] = str_replace($base_driver . '_', '', $file);
			}
			
			if (stripos($file, $compared_driver) === 0) {
				$files[$compared_driver][] = str_replace($compared_driver . '_', '', $file);
			}
		}
		
		if ($test) {
			if (!in_array($test . '_queries.log', $files[$base_driver]) || 
				!in_array($test . '_queries.log', $files[$compared_driver])) {
				echo "Logs for this test: $test are not available for both drivers\n";
			} else {
				$file1 = $base_driver . '_' . $test . '_queries.log';
				$file2 = $compared_driver . '_' . $test . '_queries.log';
				$this->_compare($file1, $file2);
			}
		} else {
			foreach($files[$base_driver] as $test_case) {
				if (in_array($test_case, $files[$compared_driver])) {
					$file1 = $base_driver . '_' . $test_case;
					$file2 = $compared_driver . '_' . $test_case;
					$this->_compare($file1, $file2);
				} else {
					echo "Comparison file for $test_case does not exist\n";
				}
			}
		}
	}
	
	protected function _compare($file1, $file2)
	{
		$fp = fopen($this->_config->queryLogPath . $file1, 'r') or exit("Unable to open file!");
		$fp2 = fopen($this->_config->queryLogPath . $file2, 'r') or exit("Unable to open file!");
		
		$ln = 0;
		while(!feof($fp) || !feof($fp2)) {
  			$line = fgets($fp);
  			$line2 = fgets($fp2);
  			$ln++;
  			
  			$data = explode('|~|', $line);
  			$data2 = explode('|~|', $line2);
  			
  			/** 
			 * data size is 3, first being SQL QUERY
  			 * second being serialized result set
  			 * third being serialized db errors array
  			 * we know the SQL queries will be different
  			 * so we will first check for errors and compare 
  			 * result sets to ensure the translation version is 
  			 * working as intended
  			 */
  			 // check errors
  			 if (isset($data[2]) && isset($data2[2]) && $data[2] != $data2[2]) {
  			 	if ($data[2] != '') {
  			 		echo $data[0] . "\n";
  			 		var_dump(unserialize($data[2]));
  			 		echo $file1 . "\n";
  			 	}
  			 	if ($data2[2] != '') {
  			 		echo $data2[0] . "\n";
  			 		var_dump(unserialize($data2[2]));
  			 		echo $file2 . "\n";
  			 	}
  			 }
  			 
  			 // check result sets
  			 if (isset($data[1]) && isset($data2[1]) && $data[1] != $data2[1]) {
  			 	if (false && $data[1] != '' && $data2[1] != '') {
  			 		$result = unserialize($data[1]);
  			 		$result2 = unserialize($data2[1]);
  			 		$count = count($result);
  			 		$count2 = count($result2);
  			 		if ($count != $count2) {
  			 			echo 'Result Count: ' . $count . "\n";
  			 			echo $data[0] . "\n";
  			 			echo $file1 . "\n";
  			 			echo 'Result Count: ' . $count2 . "\n";
  			 			echo $data2[0] . "\n";
  			 			echo $file2 . "\n";
  			 			echo "----------\n";
  			 		}
  			 	}
  			 }
  		}
		fclose($fp);
		fclose($fp2);
	}
}

$argv = $_SERVER['argv'];
array_shift($argv);

$log_compare = new LogCompare();
$log_compare->compareLogs($argv[0], $argv[1]);
