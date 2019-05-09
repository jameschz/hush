<?php
require_once '../config.php';

// -----------------------------------------------------------------------------------
// Customize these parts if you want to change the defaults.

$cas_text = "RluasYTudiTTaYsteqTERdwhaTEispYdkjsSDduyHfEWuiaQwHjfakRsjQWfasiQRuehFqQwRTenqTQfaiQGuuGdJaKpsMDVdbaXZmVweVBqjAdqDlhGdaAsFusZhVdmqVBnweQqweqJweuGFh"; // <-- This line should not be changed

// Set the path to the font(s). Example: "wp-content/fonts/"
// Empty string ("") defaults to the root of your blog.
// If you add a path, DO include the trailing slash.
$cas_fontpath = dirname(__FILE__) . "/";

$cas_fontlist = array(); // <-- This line should not be changed
// List as many TrueType font(s) as you like, one per line. Drop your own font files into this plugin's directory.
// If you are using your own fonts, make sure all fonts used are about the same default size.
// If you want some fonts to be used more frequently, enter them multiple times.
// Default freeware fonts from font101.com
$cas_fontlist[] = "arial.ttf";

// Set the anti-spam image width and height.
// You may need to increase these sizes for longer words and/or bigger fonts.
$cas_imgwidth = 100;
$cas_imgheight = 30;

// Font size for jpeg or png
$cas_font_size = 18;

// Set this to TRUE if you want to use random text colors.
// If random colors are not selected, blue text will appear on a white background, and white text will appear on black background (as decided in the next option)
$cas_randomcolors = true;

// Set the background color for the anti-spam image.
// Choose either "black" or "white"
$cas_bgcolorset = "white";

// Set noise lines or points.
$cas_noise_line = true;
$cas_noise_point = false;

// Set this to TRUE if you prefer PNG graphics (better quality text)
// Set this to FALSE if you prefer more compatable graphics (PNG crashes IE 4; JPEG does not)
$cas_UsePngNotJpeg = true;

// -----------------------------------------------------------------------------------
// You should not need to (and probably shouldn't) edit anything from here to the end.

$strlength = strlen($cas_text);
$wordcount = 5;
$start_pos = rand(0, intval($strlength - $wordcount));
// $cas_antispam = substr($cas_text, $start_pos, $wordcount);
$cas_antispam = strtoupper(substr(uniqid(), 0 - $wordcount));

// Set to php session
// Show security code for web service
session_start();
$_SESSION['securitycode'] = $cas_antispam;
if ( isset( $_GET['source'] ) ) {
	echo isset($_SESSION['securitycode']) ? $_SESSION['securitycode'] : '';
	exit;
}

// Pick a random font to use
$cas_font = $cas_fontpath . $cas_fontlist[ rand( 0, count( $cas_fontlist ) - 1 ) ];

// Set the default colors for when random text colors are not selected
if ( $cas_bgcolorset == "white") {
	$cas_textcolor = array( 0, 0, 255 ); // blue text
	$cas_bgcolor = array( 255, 255, 255); // white background
}
else {
	$cas_textcolor = array( 255, 255, 255 ); // white text
	$cas_bgcolor = array( 0, 0, 0); // black background
}

// If selected, pick a random color for the antispam word text
if( $cas_randomcolors )
{
	$cas_rand = rand( 0, 6 );
	switch( $cas_bgcolorset )
	{
		case "white":
			$cas_textcolorchoice[] = array ( 0, 0, 255 );		// blue
			$cas_textcolorchoice[] = array ( 0, 255, 255 );		// blueish
			$cas_textcolorchoice[] = array ( 0, 153, 0 );		// greenish
			$cas_textcolorchoice[] = array ( 204, 0, 0 );		// reddish
			$cas_textcolorchoice[] = array ( 255, 153, 204 );	// pinkish
			$cas_textcolorchoice[] = array ( 203, 0, 154 );		// purplish
			$cas_textcolorchoice[] = array ( 0, 0, 0 );			// black
			$cas_textcolor = $cas_textcolorchoice[$cas_rand];
			break;
		default:
			$cas_textcolor = array ( 255, 255, 255 );
			break;
	}
}

// Start building the image
$cas_image = @imagecreate( $cas_imgwidth, $cas_imgheight ) or die("Cannot Initialize new GD image stream");
$cas_bgcolor = imagecolorallocate( $cas_image, $cas_bgcolor[0], $cas_bgcolor[1], $cas_bgcolor[2] );
$cas_fontcolor = imagecolorallocate( $cas_image, $cas_textcolor[0], $cas_textcolor[1], $cas_textcolor[2] );

// Add text
$cas_angle = 3; // Degrees to tilt the text
$cas_offset = 5; // Pixels to offset the text from the border
imagettftext( $cas_image, $cas_font_size, $cas_angle, $cas_offset, $cas_imgheight - $cas_offset, $cas_fontcolor, $cas_font, $cas_antispam );

// Add noice lines
if ($cas_noise_line) {
	imagesetthickness($cas_image, 3);
	for($i=0; $i < 2; $i++)
	{  
	    $color_line = ImageColorAllocate($cas_image, mt_rand(155,255), mt_rand(155,255), mt_rand(155,255));
	    ImageArc($cas_image, mt_rand(-5,$cas_imgwidth), mt_rand(-5,$cas_imgheight), mt_rand(20,300), mt_rand(20,200), 55, 44, $color_line);
	}
	imageline($cas_image, 0, $cas_imgheight/2+8, $cas_imgwidth, $cas_imgheight/2-2, $color_line);
}

// Add noice points
// if ($cas_noise_point) {
// 	for($i=0; $i < $wordcount * 50; $i++)
// 	{  
// 	    $color_point = ImageColorAllocate($cas_image, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
// 	    ImageSetPixel($cas_image, mt_rand(0,$cas_imgwidth), mt_rand(0,$cas_imgheight), $color_point);
// 	}
// }

// Check for freetype lib, if not found default to ugly built in capability using imagechar (Lee's mod)
// Also check that the chosen TrueType font is available
if( function_exists( 'imagettftext' ) && file_exists( $cas_font ) )
{
	// Use png is available, since it produces clearer text images ; default option
	if( function_exists( 'imagepng' ) && $cas_UsePngNotJpeg )
	{
		header( "Content-type: image/png" );
		imagepng( $cas_image );
	} else {
		header( "Content-type: image/jpeg" );
		imagejpeg( $cas_image );
	}
} else {
	$cas_fontsize = 5; // 1, 2, 3, 4 or 5 (higher numbers correspond to larger font sizes)
	$tmp_len = strlen( $cas_antispam );
	for( $tmp_count = 0; $tmp_count < $tmp_len; $tmp_count++ )
	{
	   $tmp_xpos = $tmp_count * imagefontwidth( $cas_fontsize ) + 20;
	   $tmp_ypos = 10;
	   imagechar( $cas_image, $cas_fontsize, $tmp_xpos, $tmp_ypos, $cas_antispam, $cas_fontcolor );
	   $cas_antispam = substr( $cas_antispam, 1);
	}
	header("Content-Type: image/gif");
	imagegif( $cas_image );
} // end if

imagedestroy( $cas_image );
?>