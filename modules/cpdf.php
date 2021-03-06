<?php
/**
ClibPDF Functions.

See: {@link http://www.php.net/manual/en/ref.cpdf.php}
@package cpdf
*/

# FIXME: all these '1' are dummy values
define("CPDF_PM_NONE", 1);
define("CPDF_PM_OUTLINES", 1);
define("CPDF_PM_THUMBS", 1);
define("CPDF_PM_FULLSCREEN", 1);
define("CPDF_PL_SINGLE", 1);
define("CPDF_PL_1COLUMN", 1);
define("CPDF_PL_2LCOLUMN", 1);
define("CPDF_PL_2RCOLUMN", 1);

/*. bool .*/ function cpdf_global_set_document_limits(/*. int .*/ $maxPages, /*. int .*/ $maxFonts, /*. int .*/ $maxImages, /*. int .*/ $maxAnnots, /*. int .*/ $maxObjects){}
/*. bool .*/ function cpdf_set_creator(/*. int .*/ $pdfdoc, /*. string .*/ $creator){}
/*. bool .*/ function cpdf_set_title(/*. int .*/ $pdfptr, /*. string .*/ $title){}
/*. bool .*/ function cpdf_set_subject(/*. int .*/ $pdfptr, /*. string .*/ $subject){}
/*. bool .*/ function cpdf_set_keywords(/*. int .*/ $pdfptr, /*. string .*/ $keywords){}
/*. bool .*/ function cpdf_set_viewer_preferences(/*. int .*/ $pdfdoc, /*. array .*/ $preferences){}
/*. int .*/ function cpdf_open(/*. int .*/ $compression /*., args .*/){}
/*. bool .*/ function cpdf_close(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_page_init(/*. int .*/ $pdfdoc, /*. int .*/ $pagenr, /*. int .*/ $orientation, /*. int .*/ $height, /*. int .*/ $width /*., args .*/){}
/*. bool .*/ function cpdf_finalize_page(/*. int .*/ $pdfdoc, /*. int .*/ $pagenr){}
/*. bool .*/ function cpdf_set_current_page(/*. int .*/ $pdfdoc, /*. int .*/ $pagenr){}
/*. bool .*/ function cpdf_begin_text(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_end_text(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_show(/*. int .*/ $pdfdoc, /*. string .*/ $text){}
/*. bool .*/ function cpdf_show_xy(/*. int .*/ $pdfdoc, /*. string .*/ $text, /*. float .*/ $x_koor, /*. float .*/ $y_koor /*., args .*/){}
/*. bool .*/ function cpdf_continue_text(/*. int .*/ $pdfdoc, /*. string .*/ $text){}
/*. bool .*/ function cpdf_text(/*. int .*/ $pdfdoc, /*. string .*/ $text /*., args .*/){}
/*. bool .*/ function cpdf_set_font(/*. int .*/ $pdfdoc, /*. string .*/ $font, /*. float .*/ $size, /*. string .*/ $encoding){}
/*. bool .*/ function cpdf_set_font_directories(/*. int .*/ $pdfdoc, /*. string .*/ $pfmdir, /*. string .*/ $pfbdir){}
/*. bool .*/ function cpdf_set_font_map_file(/*. int .*/ $pdfdoc, /*. string .*/ $filename){}
/*. bool .*/ function cpdf_set_leading(/*. int .*/ $pdfdoc, /*. float .*/ $distance){}
/*. bool .*/ function cpdf_set_text_rendering(/*. int .*/ $pdfdoc, /*. int .*/ $rendermode){}
/*. bool .*/ function cpdf_set_horiz_scaling(/*. int .*/ $pdfdoc, /*. float .*/ $scale){}
/*. bool .*/ function cpdf_set_text_rise(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_set_text_matrix(/*. int .*/ $pdfdoc, /*. array .*/ $matrix){}
/*. bool .*/ function cpdf_set_text_pos(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y /*., args .*/){}
/*. bool .*/ function cpdf_rotate_text(/*. int .*/ $pdfdoc, /*. float .*/ $angle){}
/*. bool .*/ function cpdf_set_char_spacing(/*. int .*/ $pdfdoc, /*. float .*/ $space){}
/*. bool .*/ function cpdf_set_word_spacing(/*. int .*/ $pdfdoc, /*. float .*/ $space){}
/*. float .*/ function cpdf_stringwidth(/*. int .*/ $pdfdoc, /*. string .*/ $text){}
/*. bool .*/ function cpdf_save(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_restore(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_translate(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y){}
/*. bool .*/ function cpdf_scale(/*. int .*/ $pdfdoc, /*. float .*/ $x_scale, /*. float .*/ $y_scale){}
/*. bool .*/ function cpdf_rotate(/*. int .*/ $pdfdoc, /*. float .*/ $angle){}
/*. bool .*/ function cpdf_setflat(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_setlinejoin(/*. int .*/ $pdfdoc, /*. int .*/ $value){}
/*. bool .*/ function cpdf_setlinecap(/*. int .*/ $pdfdoc, /*. int .*/ $value){}
/*. bool .*/ function cpdf_setmiterlimit(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_setlinewidth(/*. int .*/ $pdfdoc, /*. float .*/ $width){}
/*. bool .*/ function cpdf_setdash(/*. int .*/ $pdfdoc, /*. int .*/ $white, /*. int .*/ $black){}
/*. bool .*/ function cpdf_moveto(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y /*., args .*/){}
/*. bool .*/ function cpdf_rmoveto(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y /*., args .*/){}
/*. bool .*/ function cpdf_curveto(/*. int .*/ $pdfdoc, /*. float .*/ $x1, /*. float .*/ $y1, /*. float .*/ $x2, /*. float .*/ $y2, /*. float .*/ $x3, /*. float .*/ $y3 /*., args .*/){}
/*. bool .*/ function cpdf_lineto(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y /*., args .*/){}
/*. bool .*/ function cpdf_rlineto(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y /*., args .*/){}
/*. bool .*/ function cpdf_circle(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y, /*. float .*/ $radius /*., args .*/){}
/*. bool .*/ function cpdf_arc(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y, /*. float .*/ $radius, /*. float .*/ $start, /*. float .*/ $end /*., args .*/){}
/*. bool .*/ function cpdf_rect(/*. int .*/ $pdfdoc, /*. float .*/ $x, /*. float .*/ $y, /*. float .*/ $width, /*. float .*/ $height /*., args .*/){}
/*. bool .*/ function cpdf_newpath(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_closepath(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_closepath_stroke(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_stroke(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_fill(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_fill_stroke(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_closepath_fill_stroke(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_clip(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_setgray_fill(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_setgray_stroke(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_setgray(/*. int .*/ $pdfdoc, /*. float .*/ $value){}
/*. bool .*/ function cpdf_setrgbcolor_fill(/*. int .*/ $pdfdoc, /*. float .*/ $red, /*. float .*/ $green, /*. float .*/ $blue){}
/*. bool .*/ function cpdf_setrgbcolor_stroke(/*. int .*/ $pdfdoc, /*. float .*/ $red, /*. float .*/ $green, /*. float .*/ $blue){}
/*. bool .*/ function cpdf_setrgbcolor(/*. int .*/ $pdfdoc, /*. float .*/ $red, /*. float .*/ $green, /*. float .*/ $blue){}
/*. bool .*/ function cpdf_set_page_animation(/*. int .*/ $pdfdoc, /*. int .*/ $transition, /*. float .*/ $duration, /*. float .*/ $direction, /*. int .*/ $orientation, /*. int .*/ $inout){}
/*. bool .*/ function cpdf_finalize(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_output_buffer(/*. int .*/ $pdfdoc){}
/*. bool .*/ function cpdf_save_to_file(/*. int .*/ $pdfdoc, /*. string .*/ $filename){}
/*. bool .*/ function cpdf_import_jpeg(/*. int .*/ $pdfdoc, /*. string .*/ $filename, /*. float .*/ $x, /*. float .*/ $y, /*. float .*/ $angle, /*. float .*/ $width, /*. float .*/ $height, /*. float .*/ $x_scale, /*. float .*/ $y_scale, /*. int .*/ $gsave /*., args .*/){}
/*. bool .*/ function cpdf_place_inline_image(/*. int .*/ $pdfdoc, /*. int .*/ $gdimage, /*. float .*/ $x, /*. float .*/ $y, /*. float .*/ $angle, /*. float .*/ $width, /*. float .*/ $height, /*. int .*/ $gsave /*., args .*/){}
/*. bool .*/ function cpdf_add_annotation(/*. int .*/ $pdfdoc, /*. float .*/ $xll, /*. float .*/ $yll, /*. float .*/ $xur, /*. float .*/ $yur, /*. string .*/ $title, /*. string .*/ $text /*., args .*/){}
/*. bool .*/ function cpdf_set_action_url(/*. int .*/ $pdfdoc, /*. float .*/ $xll, /*. float .*/ $yll, /*. float .*/ $xur, /*. float .*/ $yur, /*. string .*/ $url /*., args .*/){}
/*. int .*/ function cpdf_add_outline(/*. int .*/ $pdfdoc, /*. int .*/ $lastoutline, /*. int .*/ $sublevel, /*. int .*/ $open, /*. int .*/ $pagenr, /*. string .*/ $title){}
