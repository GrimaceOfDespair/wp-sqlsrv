<?php
/**
 * Fields mapping
 *
 * Some column types from MySQL 
 * don't have an exact equivalent for SQL Server
 * That is why we need to know what column types they were
 * originally to make the translations. 
 *
 * @category MSSQL
 * @package MySQL_Translations
 * @author A.Garcia & A.Gentile
 * */
class Fields_map
{
    var $fields_map = array();
    var $filepath = '';

	/**
     * php4 style call to constructor.
     * 
     * @since 2.7.1
     *
     */
    function Fields_map()
    {
		return $this->__construct();
    }
	
	/**
	 * Set filepath
	 *
	 * PHP5 style constructor for compatibility with PHP5.
	 *
	 * @since 2.7.1
	 */
	function __construct()
	{
	    $this->filepath = trim(str_replace('mu-plugins/wp-db-abstraction/translations/sqlsrv', '', strtr(dirname(__FILE__), '\\', '/')), '/') . '/fields_map.parsed_types.php';
	}

    /**
     * Get array of fields by type from fields_map property
     *
     * @since 2.8
     * @param $type
     * @param $table
     *
     * @return array
     */
    function by_type($type, $table = null)
    {
        $ret = array();
        foreach ($this->fields_map as $tables => $fields) {
            if ( is_array($fields) ) {
                foreach ($fields as $field_name => $field_meta) {
                    if ( $field_meta['type'] == $type ) {
                        if (is_null($table) || $tables == $table) {
                            $ret[] = $field_name;
                        }
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Get array of tables from fields_map property
     *
     * @since 2.8
     *
     * @return array
     */
    function get_tables()
    {
        $ret = array();
        foreach ($this->fields_map as $tables => $fields) {
            $ret[] = $tables;
        }
        return $ret;
    }

    /**
     * Given a query find the column types
     *
     * @since 2.8
     * @param $qry
     *
     * @return array
     */
    function extract_column_types($qry)
    {
        //table name
        $matches = array();
		if (preg_match('/[CREATE|ALTER] TABLE (.*) \(/i',$qry,$matches)){
			$table_name = $matches[1];
		} else {
			$table_name = '';
		}


		$fields_and_indexes = substr($qry,strpos($qry,'(')+1,strrpos($qry,')')-(strpos($qry,'(')+1));
		$just_fields = trim(substr($fields_and_indexes,0,$this->index_pos($fields_and_indexes)));

		$field_lines = explode(',',$just_fields);
		$field_types = array();
		foreach ($field_lines as $field_line) {
			if (!empty($field_line)){
				$field_line = trim($field_line);
			$words = explode(' ',$field_line,3);
			$first_word = $words[0];
			$field_type = $this->type_translations($words[1]);
			if ($field_type !== false) {
				$field_types[$first_word] = array('type'=>$field_type);
			}
			}
		}

		//get primary key
		$just_indexes = trim(substr($fields_and_indexes,$this->index_pos($fields_and_indexes)));
		$matches = array();
		$has_primary_key = preg_match('/PRIMARY KEY *\((.*?)[,|\)]/i',$just_indexes,$matches);
		if ($has_primary_key) {
			$primary_key = trim($matches[1]);
			$field_types[$primary_key] = array('type' => 'primary_id');
		}
		ksort($field_types);

		return array($table_name => $field_types);
    }

    /**
     * According to the column types in MySQL
     *
     * @since 2.8
     * @param $field_type
     *
     * @return array
     */
    function type_translations($field_type)
    {
        //false means not translate this field.
        $translations = array(
			array('pattern' => '/varchar(.*)/', 'trans' => 'nvarchar'),
			array('pattern' => '/.*text.*/',    'trans' => 'nvarchar'),
			array('pattern' => '/.*datetime.*/','trans' => 'date'),
			array('pattern' => '/int(.*)/',     'trans' => 'int'),
		);

		$res = '';
		while (($res === '') && ($trans = array_shift($translations))) {
			if (preg_match($trans['pattern'],$field_type)) {
				$res = $trans['trans'];
			}
		}
	  
		if ($res === '') {
			$res = $field_type;
		}
		return $res;  
    }


    /**
     * Get array of tables from fields_map property
     *
     * @since 2.8
     * @param $fields_and_indexes
     *
     * @return array
     */
    function index_pos($fields_and_indexes)
    {
        $reserved_words = array('PRIMARY KEY', 'UNIQUE');
		$res = false;
		while (($res === false) && ($reserved_word = array_shift($reserved_words))){
			$res = stripos($fields_and_indexes,$reserved_word);
		}
	
		return $res;
    }

    /**
     * Update fields may given a CREATE | ALTER query
     *
     * @since 2.8
     * @param $qry
     *
     * @return array
     */
    function update_for($qry)
    {
        $this->read();
		$this->fields_map = array_merge($this->fields_map, $this->extract_column_types($qry));
		file_put_contents($this->filepath, '<?php return ' . var_export($this->fields_map, true) . "\n ?>");
		return $this->fields_map;
    }

    /**
     * Get the fields_map from memory or from the file.
     *
     * @since 2.8
     *
     * @return array
     */
    function read()
    {
		if (empty($this->fields_map)) {
			if (file_exists($this->filepath)) {
				$this->fields_map = require($this->filepath);
			} else {
				$this->fields_map = array();
			}
		}
		return $this->fields_map;
    }

}
