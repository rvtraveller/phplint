<?php
/** YP/NIS Functions.

See: {@link http://www.php.net/manual/en/ref.nis.php}
@package yp
*/


# FIXME: dummy values
define('YPERR_BADARGS', 1);
define('YPERR_BADDB', 1);
define('YPERR_BUSY', 1);
define('YPERR_DOMAIN', 1);
define('YPERR_KEY', 1);
define('YPERR_MAP', 1);
define('YPERR_NODOM', 1);
define('YPERR_NOMORE', 1);
define('YPERR_PMAP', 1);
define('YPERR_RESRC', 1);
define('YPERR_RPC', 1);
define('YPERR_YPBIND', 1);
define('YPERR_YPERR', 1);
define('YPERR_YPSERV', 1);
define('YPERR_VERS', 1);

/*. string.*/ function yp_get_default_domain(){}
/*. int   .*/ function yp_order(/*. string .*/ $domain, /*. string .*/ $map){}
/*. string.*/ function yp_master(/*. string .*/ $domain, /*. string .*/ $map){}
/*. string.*/ function yp_match(/*. string .*/ $domain, /*. string .*/ $map, /*. string .*/ $key){}
/*. array .*/ function yp_first(/*. string .*/ $domain, /*. string .*/ $map){}
/*. array .*/ function yp_next(/*. string .*/ $domain, /*. string .*/ $map, /*. string .*/ $key){}
/*. bool  .*/ function yp_all(/*. string .*/ $domain, /*. string .*/ $map, /*. string .*/ $string_){}
/*. array .*/ function yp_cat(/*. string .*/ $domain, /*. string .*/ $map){}
/*. int   .*/ function yp_errno(){}
/*. string.*/ function yp_err_string(/*. int .*/ $errorcode){}
