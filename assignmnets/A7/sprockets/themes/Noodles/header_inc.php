<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<?php include INCLUDE_PATH . 'meta_inc.php'; ?>
	<link href="<?php echo THEME_PATH; ?>css/style.css" rel="stylesheet" type="text/css" />
	<!--[if lte IE 7]><link href="<?php echo THEME_PATH; ?>css/iehacks.css" rel="stylesheet" type="text/css" /><![endif]-->
	<script type="text/javascript" src="<?php echo THEME_PATH; ?>js/jquery.js"></script>
	<!--[if IE 6]>
	<script type="text/javascript" src="<?php echo THEME_PATH; ?>js/ie6pngfix.js"></script>
	<script type="text/javascript">
		DD_belatedPNG.fix('img, ul, ol, li, div, p, a, h1, h2, h3, h4, h5, h6, span');
	</script>
	<![endif]-->
</head>

<body id="page">

<!-- wrapper -->
<div class="rapidxwpr floatholder">
  <div class="rapidxwpr-bottom">
  
    <!-- header -->
    <div id="header">
    
      <!-- logo -->
      <a href="index.html"><img id="logo" class="correct-png" src="<?php echo THEME_PATH; ?>images/logo.png" alt="Home" title="Home" /></a>
      <!-- / logo -->
      
      <!-- quote -->
      <div class="quote">
        <img src="<?php echo THEME_PATH; ?>images/quote.png" alt="Quote" />
      </div>
      <!-- / quote -->
      
      <!-- topmenu -->
      <div id="topmenu">
        <ul>
		<?php echo noodleTopMenu($config->nav1,'<li>','</li>'); ?>
		 <!--
          <li><a href="" class="active"><span>Home</span></a></li>
          <li><a href=""><span>About</span></a></li>
          <li><a href=""><span>Blog</span></a></li>
          <li><a href=""><span>Press</span></a></li>
          <li><a href=""><span>Contact</span></a></li>
		  -->
        </ul>
      </div>
      <!-- / topmenu -->
    
    </div>
    <!-- / header -->
    
    <!-- main body -->
    <div id="middle">
      <div class="background layoutleft">
      
        <div id="main" class="clearingfix">
          <div id="mainmiddle" class="floatbox withright">
          
            <?php noodle_sidebar2(); ?> 
            <!-- content column -->
            <div id="content" class="clearingfix">
              <div class="floatbox">
              <!-- header ends here -->
			  
<?php
/**
 * Encapsulates sidebar2 data into a theme specific styled div  
 *
 * @param none
 * @return none (echos sidebar data to page, if applicable) 
 * @todo none
 */ 
function noodle_sidebar2()
{
	global $config;
	if($config->sidebar2 != "")
	{
		?>
		    <!-- right column -->
            <div id="right" class="clearingfix">
            
              <!-- about -->
              <div class="about">
                <?php echo $config->sidebar2; ?>
              </div>
              <!-- / about -->
			</div>
            <!-- / right column -->
		<?php
	
	}
	
}

/* 
 * allows creation of links from associative array of link data and 
 * HTML prefix & suffix to each link
 *
 * based on makeLinks() function from common_inc.php - adds <span> around text
 *
 * Link arrays created in config_inc.php, and noodleTopMenu() function called in 
 * header or footer include as required.
 *
 * Allows different HTML treatments per header/footer combo while containing 
 * same links in different header/footer combos, if desired
 *
 * <code>
 * $nav1 = array();
 * $nav1['index.php'] = "INDEX";
 * $nav1['about_us.php'] = "ABOUT US";
 * $nav1['contact.php'] = "CONTACT";
 * $nav1['links.php'] = "LINKS";
 * echo noodleTopMenu($nav1,'<li>','</li>');
 * </code>
 *
 * @param array $linkArray associative array of link data
 * @param str $prefix optional HTML string to add to front of link
 * @param str $suffix optional HTML string to add to end of link
 * @return str link segment as created by array
 */

function noodleTopMenu($linkArray,$prefix='',$suffix='',$separator="~")
{
	$myReturn = '';
	foreach($linkArray as $url => $text)
	{
		$target = ' target="_blank"'; #will be removed if relative URL
		if(basename($url) == THIS_PAGE){$myClass = ' class="active" ';}else{$myClass = '';} #added to create PageID
		$httpCheck = strtolower(substr($url,0,8)); # http:// or https://
		if(!(strrpos($httpCheck,"http://")>-1) && !(strrpos($httpCheck,"https://")>-1))
		{//relative url - add path
			$url = VIRTUAL_PATH . $url;
			$target = "";
		}else if(strrpos($url,ADMIN_PATH . 'admin_')>-1){$target = "";}# clear target="_blank" for admin files
		$pos = strrpos($text, $separator); #tilde as default separator
		if ($pos === false)
		{// note: three equal signs - not found!
			$myReturn .= $prefix . "<a href=\"" . $url . "\"" . $target . $myClass . "><span>" . $text . "</span></a>" . $suffix . "\n";
		}else{//found!  explode into title!
			$aText = explode($separator,$text); #split into an array on separator
			$myReturn .= $prefix . "<a href=\"" . $url . "\" title=\"" . $aText[1] . "\"" . $target . $myClass . "><span>" . $aText[0] . "</span></a>" . $suffix . "\n";	
		}
	}	
	return $myReturn;	
}
?>