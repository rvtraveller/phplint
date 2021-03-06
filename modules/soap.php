<?php
/** SOAP Functions.

See: {@link http://www.php.net/manual/en/ref.soap.php}
@package soap
*/

define('SOAP_1_1', 1);
define('SOAP_1_2', 1);
define('SOAP_PERSISTENCE_SESSION', 1);
define('SOAP_PERSISTENCE_REQUEST', 1);
define('SOAP_FUNCTIONS_ALL', 1);
define('SOAP_ENCODED', 1);
define('SOAP_LITERAL', 1);
define('SOAP_RPC', 1);
define('SOAP_DOCUMENT', 1);
define('SOAP_ACTOR_NEXT', 1);
define('SOAP_ACTOR_NONE', 1);
define('SOAP_ACTOR_UNLIMATERECEIVER', 1);
define('SOAP_COMPRESSION_ACCEPT', 1);
define('SOAP_COMPRESSION_GZIP', 1);
define('SOAP_COMPRESSION_DEFLATE', 1);
define('SOAP_AUTHENTICATION_BASIC', 1);
define('SOAP_AUTHENTICATION_DIGEST', 1);
define('UNKNOWN_TYPE', 1);
define('XSD_STRING', 1);
define('XSD_BOOLEAN', 1);
define('XSD_DECIMAL', 1);
define('XSD_FLOAT', 1);
define('XSD_DOUBLE', 1);
define('XSD_DURATION', 1);
define('XSD_DATETIME', 1);
define('XSD_TIME', 1);
define('XSD_DATE', 1);
define('XSD_GYEARMONTH', 1);
define('XSD_GYEAR', 1);
define('XSD_GMONTHDAY', 1);
define('XSD_GDAY', 1);
define('XSD_GMONTH', 1);
define('XSD_HEXBINARY', 1);
define('XSD_BASE64BINARY', 1);
define('XSD_ANYURI', 1);
define('XSD_QNAME', 1);
define('XSD_NOTATION', 1);
define('XSD_NORMALIZEDSTRING', 1);
define('XSD_TOKEN', 1);
define('XSD_LANGUAGE', 1);
define('XSD_NMTOKEN', 1);
define('XSD_NAME', 1);
define('XSD_NCNAME', 1);
define('XSD_ID', 1);
define('XSD_IDREF', 1);
define('XSD_IDREFS', 1);
define('XSD_ENTITY', 1);
define('XSD_ENTITIES', 1);
define('XSD_INTEGER', 1);
define('XSD_NONPOSITIVEINTEGER', 1);
define('XSD_NEGATIVEINTEGER', 1);
define('XSD_LONG', 1);
define('XSD_INT', 1);
define('XSD_SHORT', 1);
define('XSD_BYTE', 1);
define('XSD_NONNEGATIVEINTEGER', 1);
define('XSD_UNSIGNEDLONG', 1);
define('XSD_UNSIGNEDINT', 1);
define('XSD_UNSIGNEDSHORT', 1);
define('XSD_UNSIGNEDBYTE', 1);
define('XSD_POSITIVEINTEGER', 1);
define('XSD_NMTOKENS', 1);
define('XSD_ANYTYPE', 1);
define('XSD_ANYXML', 1);
define('SOAP_ENC_OBJECT', 1);
define('SOAP_ENC_ARRAY', 1);
define('XSD_1999_TIMEINSTANT', 1);
define('SOAP_SINGLE_ELEMENT_ARRAYS', 1);
define('SOAP_WAIT_ONE_WAY_CALLS', 1);
define('WSDL_CACHE_NONE', 1);
define('WSDL_CACHE_DISK', 1);
define('WSDL_CACHE_MEMORY', 1);
define('WSDL_CACHE_BOTH', 1);
define('XSD_NAMESPACE', '?');
define('XSD_1999_NAMESPACE', '?');

class SoapParam
{
	/*. void .*/ function __construct(/*. mixed .*/ $data, /*. string .*/ $name){}
}

class SoapHeader
{
	/*. void .*/ function __construct(/*. string .*/ $namespace_, /*. string .*/ $name /*. , args .*/){}
}

class SoapFault
{
	/*. void .*/ function __construct(/*. string .*/ $faultcode, /*. string .*/ $faultstring /*. , args .*/){}
}

class SoapVar
{
	/*. void .*/ function __construct(/*. mixed .*/ $data, /*. int .*/ $encoding /*. , args .*/){}
}

class SoapServer
{
	/*. void .*/ function __construct(/*. string .*/ $wsdl /*. , args .*/){}
	/*. void .*/ function addFunction(/*. mixed .*/ $functions){}
	/*. object .*/ function setPersistence(/*. int .*/ $mode){}
	/*. void .*/ function setClass(/*. string .*/ $class_name /*. , args .*/){}
	/*. void .*/ function setObject(/*. object .*/ $obj){}
	/*. array .*/ function getFunctions(){}
	/*. void .*/ function handle(/*. args .*/){}
	/*. void .*/ function fault(/*. string .*/ $code, /*. string .*/ $str /*. , args .*/){}
}


class SoapClient
{
	/*. void .*/ function __construct(){}

	/** @deprecated Use {@link self::__soapCall()} instead. */
	/*. mixed .*/ function __call(/*. string .*/ $function_name, /*. array[int]mixed .*/ $arguments /*. , args .*/){}

	/*. mixed .*/ function __soapCall(/*. string .*/ $function_name, /*. array[int]mixed .*/ $arguments /*. , args .*/){}
	/*. string .*/ function __doRequest(){}
	/*. array .*/ function __getFunctions(){}
	/*. string .*/ function __getLastRequest(){}
	/*. string .*/ function __getLastRequestHeaders(){}
	/*. object .*/ function __getLastResponse(){}
	/*. string .*/ function __getLastResponseHeaders(){}
	/*. array .*/ function __getTypes(){}
	/*. void .*/ function __setCookie(/*. string .*/ $name /*. , args .*/){}
	/*. void .*/ function __setSoapHeaders(/*. array[int]SoapHeader .*/ $hdrs){}
	/*. string .*/ function __setLocation(/*. args .*/){}
}
