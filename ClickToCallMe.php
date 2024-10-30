<?php
/*
Plugin Name: Click To Call Me
Plugin URI: http://www.igloobo.com/clicktocallme/howitworks.aspx
Version: 1.1
Author: Federico De Gioannini, <a target="_blank" href="http://www.xenialab.com/">XeniaLAB</a> and <a target="_blank" href="http://www.gloobo.it/">Gloobo</a>
Author URI: http://www.hand4shake.com/
Description: Click To Call Me is a plug-in developed by Gloobo and XeniaLAB, that allows your website visitors to call you for free, from everywhere in the world, directly on your telephone (landline or mobile). Clients, buyers, or shoppers no longer need to leave your website or even their computer to initiate a phone call.
Features

- SEARCH FUNCTION - choose up to 3 numbers you want to be called to: if you are not available at the first one ClickToCallMe will search you on the other ones. 
- AVAILABILITY FUNCTION - set up days and hours you want to be contacted.
- ANSWERING SERVICE
- CUSTOMIZABLE - choose color, text and the answering service message.  
- TOP UP CREDIT IN A SAFE ENVIRONMENT, and also use it to call abroad from your phones.  

More information about how it works, rates and how to set all the functions on the Gloobo Click To Call Me page.

*/

//********************************
//            LICENSE
//********************************
/*

This software is distributed under GNU GPL license.
For more informations, please read the file "license.txt"

*/


//********************************
//		FUNCTIONS
//********************************

// action function for above hook
function ClicktoCallme_add_pages() {
    
    // Add a new top-level menu 
    add_menu_page('Click To Call Me settings', 'Click To Call Me', 'administrator', 'Click To Call Me settings', 'ClicktoCallme_toplevel_page', get_bloginfo('url').'/wp-content/plugins/click-to-call-me/img/click2call2.png');

}


// WebCallButton_toplevel_page() displays the page content for the custom Test Toplevel menu
function ClicktoCallme_toplevel_page() {

// Read in existing options value from database

    $gbtitle = get_option( 'gbtitle' );
    $gtelephone = "click2call";
    $gtuser = "click2call";
    $gtpassword = "click2call";
    $gtmailbox = "";
    $gtserver = "sip.igloobo.com";
    $gtinterface = get_option( 'ginterface' );
    $gtcall = get_option( 'gnumber2call');

	

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if (( $_POST[ 'hf_gbtitle' ] == 'Y' )|| ( $_POST[ 'ghf_ginterface' ] == 'Y' || ( $_POST[  'ghf_gnumber2call' ] == 'Y' ) )) {

        // Read their posted value

	$gbtitle = stripslashes($_POST[ 'gbtitle' ]);
	$gtcall = stripslashes($_POST[ 'gnumber2call' ]);
	

	if (stripslashes($_POST[ 'ginterface' ]) == "")
			$gtinterface = "http://sip.igloobo.com/h4sproject/webapps/sip/button_green/";
		
		elseif (substr($_POST[ 'ginterface' ], -1) != "/")
			$gtinterface = stripslashes($_POST[ 'ginterface' ]).'/';

		else
			$gtinterface = stripslashes($_POST[ 'ginterface' ]);

	
        // Save the posted value in the database


        update_option( 'gbtitle' , $gbtitle );
 	  update_option( 'ginterface' , $gtinterface );
        update_option( 'gnumber2call' , $gtcall );


if (file_exists(xenialabc2c)== FALSE)
	mkdir (xenialabc2c);

//Clean

$file = "c2c.xml";
if (file_exists($file)) {
 unlink ($file);
}


// .htaccess definition
	

if(preg_match("/^http:\/\/([^\/]*)\//",$gtinterface,$arr))
	$ip=$arr[1];
else
	$ip=$gtinterface;
$text = fopen("xenialabc2c/.htaccess", "w+");

$line = "order deny,allow\nDeny from all\nAllow from ".gethostbyname($ip);


fwrite($text, $line);
fclose($text); 


// XML definition	

$text = fopen("xenialabc2c/c2c.xml", "w+");

$line = "\n<config>\n	<title>$gbtitle</title>\n	<telephone>$gtelephone</telephone>\n	<user>$gtuser</user>\n	<password>$gtpassword</password>\n	<mailbox>$gtmailbox</mailbox>\n	<server>$gtserver</server>\n	<t_number>$gtcall</t_number>\n</config>";
fwrite($text, $line);

fclose($text);


        // Put an options updated message on the screen
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'Click to Call-me_domain' ); ?></strong></p></div>
<?php

 }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header


    ?>  <a  style="float: left; title="Click to Call-me" rel="alternate" type="application/rss+xml" ><img src=" <?php echo get_bloginfo('url').'/wp-content/plugins/click-to-call-me/img/click2call.png' ?>" alt="" style="border:0" width="45" height="45"/></a>  <?php
    echo "<h2>"  . __( 'Click To Call Me settings', 'ClicktoCall-me_domain' ) . "</h2>";
    echo "<h4>" . __( 'Click To Call Me control panel and information links', 'ClicktoCall-me_domain' ) . "</h4>";

    // options form
    
    ?>


<form name="form1" method="post" action="">

<input type="hidden" name="<?php echo 'hf_gbtitle'; ?>" value="Y">
<input type="hidden" name="<?php echo 'hf_ginterface'; ?>" value="Y"> 
<input type="hidden" name="<?php echo 'hf_gnumber2call'; ?>" value="Y">


<table class="form-table">

<tr>

<td>
<span style="font-size: 10.0pt; font-family: Verdana">
<b>Button Text:</b>
</span>
</td>

<td><input type="text" size="15" name="<?php echo 'gbtitle'; ?>" value="<?php echo $gbtitle; ?>" maxlength="12" ></td>
<td>
<span style="font-size: 8.0pt; font-family: Verdana">
Edit the text displayed on the button.<br>
(ex: Call Me, Call US, Contact Me ..) Max length: 12 characters.
</span>
</td>

</tr>

<tr>
<td>
<span style="font-size: 10.0pt; font-family: Verdana">
<b>Button Skin:</b>
</span></td>

<td>
<select name="<?php echo 'ginterface'; ?>">

<?php 

if ($gtinterface == "http://sip.igloobo.com/h4sproject/webapps/sip/button_green_1_1/") {
echo "<option value=\"http://sip.igloobo.com/h4sproject/webapps/sip/button_green_1_1/\" selected>Green (Gloobo)</option>";
} else {
 echo "<option value=\"http://sip.igloobo.com/h4sproject/webapps/sip/button_green_1_1/\">Green (Gloobo)</option>";
}

if ($gtinterface == "http://sip.igloobo.com/h4sproject/webapps/sip/button_orange_1_1/") { 
echo "<option value=\"http://sip.igloobo.com/h4sproject/webapps/sip/button_orange_1_1/\" selected>Orange (Gloobo)</option>";
} else {
echo "<option value=\"http://sip.igloobo.com/h4sproject/webapps/sip/button_orange_1_1/\">Orange (Gloobo)</option>";
}

if ($gtinterface == "http://share.hand4shake.com/h4sproject/webapps/sip/button_green_1_1/") {
echo "<option value=\"http://share.hand4shake.com/h4sproject/webapps/sip/button_green_1_1/\" selected>Green (XeniaLAB)</option>";
} else {
echo "<option value=\"http://share.hand4shake.com/h4sproject/webapps/sip/button_green_1_1/\">Green (XeniaLAB)</option>";
}

if ($gtinterface == "http://share.hand4shake.com/h4sproject/webapps/sip/button_orange_1_1/") {
echo "<option value=\"http://share.hand4shake.com/h4sproject/webapps/sip/button_orange_1_1/\" selected>Orange (XeniaLAB)</option>";
} else {
echo "<option value=\"http://share.hand4shake.com/h4sproject/webapps/sip/button_orange_1_1/\">Orange (XeniaLAB)</option>";
}

?>

</select>
</td>

<td>
<span style="font-size: 8.0pt; font-family: Verdana">
Choose the button color.
</span>
</td>

</tr>

<tr>
<td>
<span style="font-size: 10.0pt; font-family: Verdana">
<b>Activation Key:</b>
</span>
</td>

<td><input type="text" name="<?php echo 'gnumber2call'; ?>" value="<?php echo $gtcall; ?>" size="20"></td>

</td>
<td>
<span style="font-size: 8.0pt; font-family: Verdana">
You need to enter the Activation Key for the plugin to work.<br>
<a target="_blank" href="http://www.igloobo.com/clicktocallme/configuration.aspx">
Get the Activation Key.
</a></span></td>

</tr>
</table>


<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'ClicktoCall-me_domain' ) ?>" />
</p>

</form>
</div>

<?php
 echo "\n";	
 echo "\n";
 echo "<h4>" . __( 'About Gloobo Click To Call Me', 'ClicktoCall-me_domain' ) . "</h4>";
?>

<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
Click To Call Me is a plug-in developed by Gloobo, that allows your website visitors to call you for free, from everywhere in the world, directly <br>
on your telephone (landline or mobile). Clients, buyers, or shoppers no longer need to leave your website or even their computer to initiate a phone call.<br>
</span>
</span></p>

<br>

<?php
 echo "\n";
 echo "<h4>" . __( 'Features', 'ClicktoCall-me_domain' ) . "</h4>";
?>


<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
<blockquote>
<li type=disc>SEARCH FUNCTION - choose up to 3 numbers you want to be called to: if you are not available at the first one Click To Call Me will search you on<br> the other ones.</li>
</blockquote>
</span>
</p>
<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
<blockquote>
<li type=disc>AVAILABILITY FUNCTION - set up days and hours you want to be contacted.</li>
</blockquote>
</span>
</p>
<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
<blockquote>
<li type=disc>ANSWERING SERVICE</li>
</blockquote>
</span>
</p>
<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
<blockquote>
<li type=disc>CUSTOMIZABLE - choose color, text and the answering service message.</li>  
</blockquote>
</span>
</p>
<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
<blockquote>
<li type=disc>TOP UP CREDIT IN A SAFE ENVIRONMENT, and also use it to call abroad from your phones.</li>
</blockquote>
</span>
</p>
<br>

<p class="MsoNormal">
<span style="font-size: 10.0pt; font-family: Verdana">
More information about how it works, rates and how to set all the functions on the 
<a target="_blank" href="http://www.igloobo.com/clicktocallme/howitworks.aspx">
Gloobo Click To Call Me page.</a></span></font></p>
</a></span></p>


<?php

}



function widg_menu(){
  $data = get_option('gphone_title');
  ?>
  <p><label>Title:  <input name="gphone_title" type="text" value="<?php echo $data['gtitle']; ?>" /></label></p>
  

  <?php

   if (isset($_POST['gphone_title'])){
    $data['gtitle'] = attribute_escape($_POST['gphone_title']);
    update_option('gphone_title', $data);
 
 }
}


function phone_widg() {
	
	$gptitle = get_option('gphone_title');
	$gptitle = $gptitle['gtitle'];

	echo"<h2>$gptitle</h2>";
	
        $gbtitle = get_option( 'gbtitle' );
	$gtelephone = get_option( 'gtelephone' );
        $gtuser = get_option( 'guser' );
        $gtpassword = get_option( 'gpassword' );
        $gtmailbox = get_option( 'gmailbox' );
        $gtserver = get_option( 'gserver' );
	$gtinterface = get_option( 'ginterface' );
	$gtaddress = get_bloginfo('url');
        $gtcall = get_bloginfo('gnumber2call');

?>

<br>
<div>


<!--  BEGIN Browser History required section -->
<link rel="stylesheet" type="text/css" href="<?=$gtinterface?>history/history.css" />
<!--  END Browser History required section -->

<script src="<?=$gtinterface?>AC_OETags.js" language="javascript"></script>

<!--  BEGIN Browser History required section -->
<script src="<?=$gtinterface?>history/history.js" language="javascript"></script>
<!--  END Browser History required section -->


<script type="text/javascript">

function c2c_getConfig() {
	
	return { address: '<?=$gtaddress?>', flashserver: '<?=$gtinterface?>', autologin: true };
};

</script>


  	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="click2call" width="220" height="140" align="center"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="<?=$gtinterface?>click2call.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="allowScriptAccess" value="always" />
			<embed src="<?=$gtinterface?>click2call.swf" quality="high" bgcolor="#ffffff"
				width="220" height="140" name="click2call" align="center"
				play="true"
				loop="false"
				quality="high"
				allowScriptAccess="always"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
	</object>

</div>
<br>
<?php

}


function init_button(){
	register_sidebar_widget('Click To Call Me', 'phone_widg');  
	register_widget_control('Click To Call Me', 'widg_menu');	
}



//*****************************************
//		ACTIONS
//*****************************************
add_action('admin_menu', 'ClicktoCallme_add_pages');

add_action('plugins_loaded', 'init_button');

?>
