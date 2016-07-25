<?php
/**
Database (dbm-style) Abstraction Layer Functions.

See:  {@link http://www.php.net/manual/en/ref.dba.php}
@package dba
*/


/*. resource .*/ function dba_popen(/*. string .*/ $path, /*. string .*/ $mode /*., args .*/){}
/*. resource .*/ function dba_open(/*. string .*/ $path, /*. string .*/ $mode /*., args .*/){}
/*. void .*/ function dba_close(/*. resource .*/ $handle){}
/*. bool .*/ function dba_exists(/*. string .*/ $key, /*. resource .*/ $handle){}
/*. string .*/ function dba_fetch(/*. string .*/ $key /*., args .*/){}
/*. array .*/ function dba_key_split(/*. string .*/ $key){}
/*. string .*/ function dba_firstkey(/*. resource .*/ $handle){}
/*. string .*/ function dba_nextkey(/*. resource .*/ $handle){}
/*. bool .*/ function dba_delete(/*. string .*/ $key, /*. resource .*/ $handle){}
/*. bool .*/ function dba_insert(/*. string .*/ $key, /*. string .*/ $value, /*. resource .*/ $handle){}
/*. bool .*/ function dba_replace(/*. string .*/ $key, /*. string .*/ $value, /*. resource .*/ $handle){}
/*. bool .*/ function dba_optimize(/*. resource .*/ $handle){}
/*. bool .*/ function dba_sync(/*. resource .*/ $handle){}
/*. array .*/ function dba_handlers( /*. args .*/){}
/*. array .*/ function dba_list(){}
