<?php
/**
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

include_once (dirname(__FILE__).DS.'ja_vars_1.5.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">

<head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); ?>

<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->baseurl(); ?>templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tmpTools->templateurl(); ?>/css/typo.css" type="text/css" />

<script language="javascript" type="text/javascript" src="<?php echo $tmpTools->templateurl(); ?>/js/ja.script.js"></script>

<!-- Menu head -->
<?php $jamenu->genMenuHead(); ?>

<link href="<?php echo $tmpTools->templateurl(); ?>/css/colors/<?php echo $tmpTools->getParam(JA_TOOL_COLOR); ?>.css" rel="stylesheet" type="text/css" />

<!--[if lte IE 6]>
<style type="text/css">
.clearfix {height: 1%;}
img {border: none;}
</style>
<![endif]-->

<!--[if gte IE 7.0]>
<style type="text/css">
.clearfix {display: inline-block;}
</style>
<![endif]-->

<?php if ($tmpTools->isIE6()) { ?>
<!--[if lte IE 6]>
<script type="text/javascript">
var siteurl = '<?php echo $tmpTools->baseurl();?>';
</script>
<![endif]-->
<?php } ?>
</head>

<body id="bd" class="<?php echo $tmpTools->getParam(JA_TOOL_SCREEN);?> fs<?php echo $tmpTools->getParam(JA_TOOL_FONT);?>" >
<a name="Top" id="Top"></a>
<ul class="accessibility">
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-content" title="<?php echo JText::_("Skip to content");?>"><?php echo JText::_("Skip to content");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-mainnav" title="<?php echo JText::_("Skip to main navigation");?>"><?php echo JText::_("Skip to main navigation");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-col1" title="<?php echo JText::_("Skip to 1st column");?>"><?php echo JText::_("Skip to 1st column");?></a></li>
	<li><a href="<?php echo $tmpTools->getCurrentURL();?>#ja-col2" title="<?php echo JText::_("Skip to 2nd column");?>"><?php echo JText::_("Skip to 2nd column");?></a></li>
</ul>

<div id="ja-wrapper">

<!-- BEGIN: HEADER -->
<div id="ja-header" class="clearfix">
	<?php
		$siteName = $tmpTools->sitename();
		if ($tmpTools->getParam('logoType')=='image') { ?>
		<h1 class="logo">
			<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $siteName; ?></span></a>
		</h1>
	<?php } else {
		$logoText = (trim($tmpTools->getParam('logoText'))=='') ? $config->sitename : $tmpTools->getParam('logoText');
		$sloganText = (trim($tmpTools->getParam('sloganText'))=='') ? JText::_('SITE SLOGAN') : $tmpTools->getParam('sloganText');	?>
		<h1 class="logo-text">
			<a href="index.php" title="<?php echo $siteName; ?>"><span><?php echo $logoText; ?></span></a>
		</h1>
		<p class="site-slogan"><?php echo $sloganText;?></p>
	<?php } ?>

	<?php if ($this->countModules('top')) { ?>
	<div id="ja-login">
		<jdoc:include type="modules" name="top" style="raw" />
	</div>
	<?php } ?>

</div>
<!-- END: HEADER -->
<center><script type="text/javascript"><!--
google_ad_client = "pub-5046766829180845";
/* cred card 728x90, created 8/19/08 */
google_ad_slot = "4153491087";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center>
<!-- BEGIN: MAIN NAVIGATION -->
<div id="ja-mainnavwrap">

	<div id="ja-mainnav">
		<?php $jamenu->genMenu (0); ?>
	</div>

	<?php if ($this->countModules('user4')) { ?>
	<div id="ja-search">
		<jdoc:include type="modules" name="user4" style="raw" />
	</div>
	<?php } ?>

</div>

<?php if ($hasSubnav) { ?>
<div id="ja-subnav" class="clearfix">
	<?php $jamenu->genMenu (1,1); ?>
</div>
<?php } ?>
<!-- END: MAIN NAVIGATION -->
<div style="background-color: #FDFF1F;">GWUA Bahamas is changing its name to Hamiliton College of the Bahamas.</div>
<!-- BEGIN: PATHWAY -->
<div id="ja-pathway">
	<strong>You are here &nbsp;:</strong><jdoc:include type="module" name="breadcrumbs" />
</div>
<!-- END: PATHWAY -->

<div id="ja-containerwrap<?php echo $divid; ?>">
<div id="ja-container">
<div id="ja-container2" class="clearfix">

  <div id="ja-mainbody" class="clearfix">

	<!-- BEGIN: CONTENT -->
	<div id="ja-content" class="clearfix">

		<jdoc:include type="message" />

		<?php if($this->countModules('topsl')) : ?>
		<!-- BEGIN: TOPSL -->
		<div id="ja-topsl">
			<jdoc:include type="modules" name="topsl" />
		</div>
		<!-- END: TOPSL -->
		<?php endif; ?>

		<div id="ja-current-content" class="clearfix">

			<jdoc:include type="component" />

			<?php if($this->countModules('banner')) : ?>
			<!-- BEGIN: BANNER -->
			<div id="ja-banner">
				<jdoc:include type="modules" name="banner" />
			</div>
			<!-- END: BANNER -->
			<?php endif; ?>

		</div>

	</div>
	<!-- END: CONTENT -->

  <?php if ($ja_right) { ?>
  <!-- BEGIN: RIGHT COLUMN -->
	<div id="ja-col2">
		<jdoc:include type="modules" name="right" style="xhtml" />
	</div>
	<!-- END: RIGHT COLUMN -->
	<?php } ?>

	</div>

	<?php if ($ja_left) { ?>
	<!-- BEGIN: LEFT COLUMN -->
	<div id="ja-col1">
		<jdoc:include type="modules" name="left" style="xhtml" />
	</div>
	<!-- END: LEFT COLUMN -->
	<?php } ?>

</div></div></div>

<?php
$spotlight = array ('user1','user2','user5','user6','user7','user8');
$botsl = $tmpTools->calSpotlight ($spotlight,$tmpTools->isOP()?100:99.9);
if( $botsl ) {
?>
<!-- BEGIN: BOTTOM SPOTLIGHT -->
<div id="ja-botsl" class="clearfix">

  <?php if( $this->countModules('user1') ) {?>
  <div class="ja-box<?php echo $botsl['user1']['class']; ?>" style="width: <?php echo $botsl['user1']['width']; ?>;">
		<jdoc:include type="modules" name="user1" style="xhtml" />
  </div>
  <?php } ?>

  <?php if( $this->countModules('user2') ) {?>
  <div class="ja-box<?php echo $botsl['user2']['class']; ?>" style="width: <?php echo $botsl['user2']['width']; ?>;">
		<jdoc:include type="modules" name="user2" style="xhtml" />
  </div>
  <?php } ?>

  <?php if( $this->countModules('user5') ) {?>
  <div class="ja-box<?php echo $botsl['user5']['class']; ?>" style="width: <?php echo $botsl['user5']['width']; ?>;">
		<jdoc:include type="modules" name="user5" style="xhtml" />
  </div>
  <?php } ?>

  <?php if( $this->countModules('user6') ) {?>
  <div class="ja-box<?php echo $botsl['user6']['class']; ?>" style="width: <?php echo $botsl['user6']['width']; ?>;">
		<jdoc:include type="modules" name="user6" style="xhtml" />
  </div>
  <?php } ?>

  <?php if( $this->countModules('user7') ) {?>
  <div class="ja-box<?php echo $botsl['user7']['class']; ?>" style="width: <?php echo $botsl['user7']['width']; ?>;">
		<jdoc:include type="modules" name="user7" style="xhtml" />
  </div>
  <?php } ?>

  <?php if( $this->countModules('user8') ) {?>
  <div class="ja-box<?php echo $botsl['user8']['class']; ?>" style="width: <?php echo $botsl['user8']['width']; ?>;">
		<jdoc:include type="modules" name="user8" style="xhtml" />
  </div>
  <?php } ?>

</div>
<!-- END: BOTTOM SPOTLIGHT -->
<?php } ?>

<!-- BEGIN: FOOTER -->
<div id="ja-footer" class="clearfix">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHVwYJKoZIhvcNAQcEoIIHSDCCB0QCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB/7s396lPI0ZOCo4/Y0fsbDfs6TDyEpatCFWooChebBxaaN7rPxL8mfGlM6trrIxqVVUaPucxYpGTefqp3a2u5a/RElawaODc/KRfcVJFLKRGuGmvEkWcOgcGxeVwslenPSzXM6OkmMwZLqVPaQwaelzUk5LQYhHrdYK+LhTjXSDELMAkGBSsOAwIaBQAwgdQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIjSfYo8YfEz+AgbCWmmYtdXuchXXLgPzkmrsZwJhkCGw0wrfQgJXlwb7OcFxjotQQSuco1350PzMXbxqmT2LtL3wWd3ViD+3XIGohxc2TSeFQWRMRQod3xD8LBzkbwu9BhUlpkeAf4/A3AOLyooF8EMITRdP4q5jAm2scKmwMtcGu3cXKtyYN4F/ZpTB5OVIiz0WP/FRgI4O3pAClKdZNuXctWsvJZ8/NqM3e0M/QwRrWyZA4vApnL+0rXqCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTA2MDQyNzA0MTIyMVowIwYJKoZIhvcNAQkEMRYEFE4A3IXupWKa5qHRaG2CT7y3Im9RMA0GCSqGSIb3DQEBAQUABIGAJ0MDEdE8dnKFGawguiAWfYNol2M05Nof8g1ug/FUbYgl/QakqP82mrTx00Y5Tc2kkb6ihgMqoJDL1jNl9cvnvoVyjBMKvnREtWNUxDQ4aDivwLz4LyhaUviZZr89AAxTB+9u+XCzPCtvya5TjKqPQiNEQWi7/3vn1HRfb0706KQ=-----END PKCS7-----">
</form>
	</td>
    <td>
	<jdoc:include type="modules" name="user3" />
	<jdoc:include type="modules" name="footer" />
	</td>
    <td><strong><a href="http://www.gwua.net/site/index.php?option=com_chronocontact&amp;Itemid=188">Apply Now!</a></strong> </td>
  </tr>
</table>
</div>
<!-- END: FOOTER -->

</div>

<jdoc:include type="modules" name="debug" />
<style type="text/css">.wow A:link {color: #ffffff}.wow A:visited {color: #ffffff}.wow A:active {color: #ffffff}.wow A:hover {color: #ffffff}</style><span class="wow"><font size="1"><a href="http://www.wow-serbia.com" title="World of Warcraft :: Serbian Community" rel="wow">wow serbia</a>  <a href="http://www.iglobal-news.com/" title="Internet Global News" rel="wow">iGlobal News</a></font></span>

</body>

</html>