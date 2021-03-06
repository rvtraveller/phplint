<?php

/*. if_php_ver_5 .*/
/**
 * Microsoft SQL Server Functions.
 * 
 * See: {@link http://www.php.net/manual/en/ref.mssql.php} @package mssql
 */
/*. end_if_php_ver .*/

/*. if_php_ver_7 .*/
/**
 * @package mssql
 * @deprecated This module has been removed in PHP 7.
 */
/*. end_if_php_ver .*/


# FIXME: dummy values
define('MSSQL_ASSOC', 1);
define('MSSQL_NUM', 1);
define('MSSQL_BOTH', 1);
define('SQLTEXT', 1);
define('SQLVARCHAR', 1);
define('SQLCHAR', 1);
define('SQLINT1', 1);
define('SQLINT2', 1);
define('SQLINT4', 1);
define('SQLBIT', 1);
define('SQLFLT4', 1);
define('SQLFLT8', 1);
define('SQLFLTN', 1);

/*. int .*/ function mssql_connect( /*. args .*/){}
/*. int .*/ function mssql_pconnect( /*. args .*/){}
/*. bool .*/ function mssql_close( /*. args .*/){}
/*. bool .*/ function mssql_select_db(/*. string .*/ $database_name /*., args .*/){}
/*. int .*/ function mssql_fetch_batch(/*. resource .*/ $result_index){}
/*. resource .*/ function mssql_query(/*. string .*/ $query /*., args .*/){}
/*. int .*/ function mssql_rows_affected(/*. resource .*/ $conn_id){}
/*. bool .*/ function mssql_free_result(/*. resource .*/ $result_index){}
/*. string .*/ function mssql_get_last_message(){}
/*. int .*/ function mssql_num_rows(/*. resource .*/ $mssql_result_index){}
/*. int .*/ function mssql_num_fields(/*. resource .*/ $mssql_result_index){}
/*. array .*/ function mssql_fetch_row(/*. resource .*/ $result_id){}
/*. object .*/ function mssql_fetch_object(/*. resource .*/ $result_id /*., args .*/){}
/*. array .*/ function mssql_fetch_array(/*. resource .*/ $result_id /*., args .*/){}
/*. array .*/ function mssql_fetch_assoc(/*. resource .*/ $result_id){}
/*. bool .*/ function mssql_data_seek(/*. resource .*/ $result_id, /*. int .*/ $offset){}
/*. object .*/ function mssql_fetch_field(/*. resource .*/ $result_id /*., args .*/){}
/*. int .*/ function mssql_field_length(/*. resource .*/ $result_id /*., args .*/){}
/*. string .*/ function mssql_field_name(/*. resource .*/ $result_id /*., args .*/){}
/*. string .*/ function mssql_field_type(/*. resource .*/ $result_id /*., args .*/){}
/*. bool .*/ function mssql_field_seek(/*. int .*/ $result_id, /*. int .*/ $offset){}
/*. string .*/ function mssql_result(/*. resource .*/ $result_id, /*. int .*/ $row, /*. mixed .*/ $field){}
/*. bool .*/ function mssql_next_result(/*. resource .*/ $result_id){}
/*. void .*/ function mssql_min_error_severity(/*. int .*/ $severity){}
/*. void .*/ function mssql_min_message_severity(/*. int .*/ $severity){}
/*. int .*/ function mssql_init(/*. string .*/ $sp_name /*., args .*/){}
/*. bool .*/ function mssql_bind(/*. resource .*/ $stmt, /*. string .*/ $param_name, /*. mixed .*/ $var_, /*. int .*/ $type /*., args .*/){}
/*. mixed .*/ function mssql_execute(/*. resource .*/ $stmt /*., args .*/){}
/*. bool .*/ function mssql_free_statement(/*. resource .*/ $result_index){}
/*. string .*/ function mssql_guid_string(/*. string .*/ $binary /*., args .*/){}
