<?php
class Db {
	
//	var $cms = NULL;
	
	var $host;
	var $db;
	var $user;	
	var $password;
	
	function Db()
	//&$cms 
	{
		@Db::__construct( );
	}
	
	function __construct( )
	{
//		$this->cms      = & $cms;
		
		$this->host     = _BD_SERVER_;
		$this->db       = _BD_NAME_;
		$this->user     = _BD_USER_;
		$this->password = _BD_PASS_;
	}
	
	function connect()
	{
		if ( mysql_connect( $this->host, $this->user, $this->password ) )
		{
			if ( mysql_select_db( $this->db ) )
			{
				mysql_set_charset(_CHARSET_);
				mysql_query('SET NAMES "'._CHARSET_.'"');
				return true;
			} else {
				die( mysql_error() );
			}
		} else {
		 die( mysql_error() );
		}
	}
	
	function res_query( $sql, $cache = false, $seconds = 600 )
	{
		if( $cache == true ) {
			$result = new MySQLCache( $sql, $seconds );
		} else {
			$result = mysql_query( $sql ) or die( mysql_error() );
		}
		
		return $result;
	}
	
	function rows_number( $result, $cache = false )
	{
		if( $cache == true ) {
			$number = $result->num_rows();
		} else {
			$number = mysql_num_rows( $result );
		}
		
		return $number;
	}
	
	function row_assoc( $result, $cache = false )
	{
		if( $cache == true ) {
			$row = $result->fetch_assoc();
		} else {
			$row = mysql_fetch_assoc( $result );
		}
		
		return $row;
	}
}

?>