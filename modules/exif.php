<?php
/**
Exif Functions.

See: {@link http://www.php.net/manual/en/ref.exif.php}
@package exif
*/

define("EXIF_USE_MBSTRING", 1);

/*. string.*/ function exif_tagname(/*. string .*/ $index){}
/*. array[string]mixed .*/ function exif_read_data(/*. string .*/ $filename /*., args .*/){}
/*. string.*/ function exif_thumbnail(/*. string .*/ $filename /*., args .*/){}
/*. int   .*/ function exif_imagetype(/*. string .*/ $imagefile){}
