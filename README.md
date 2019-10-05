#WpNonces README

## Description
By developing WordPress Plugins and Themes sometimes you need store your own Project-Settings into 
the wp_options table.
This Tool make it easier to handle the wp_nonce_* functions in a OOP-environment.

## Installation
Easiest way to install is using composer, 
but at moment is it a private Bitbucket Repository, and is not possible to use composer. 

Please clone this Repo with

`git clone https://your-account@bitbucket.org/pcservice-voll/wpnonces.git`

or download it from

[private Bitbucket Repo](https://bitbucket.org/pcservice-voll/wpnonces/)

## How to use
<section>
<p>
In general, you can use this class without further adjustment.
<br>
As an example I have created a plugin, where i will use this package to handle nonce.
</p>
</section>
<section>
<p>
<i>Step 1 :</i> require the autoload.php to make it accessible in your plugin/theme
<br>
- download the Repo and store it in vendor folder in your plugin<br>
<code>
require_once WP_PLUGIN_DIR . '/' . plugin_basename( __DIR__ ) . '/vendor/WpNonces/vendor/autoload.php';
</code> 
</p>
</section>
<section>
<p>
<i>Step 2 :</i> load an instance in your settings or options page<br>
<code>$wp_nonces = \MediaStoreNet\WpNonces\WpNonces::getInstance();</code><br>
<b>this static method allows you to use allways the same instance of the class<br>
in all your settings/options files.</b>
</p>
</section>
<section>
<p>
<i>Step 3:</i> create a form<br>
In my case i do this on a separate function and call these in my settings/options page<br>
<pre> <code>
function my_form( $wp_nonces ) {
	ob_start();
	
    <form method="get" action="options.php">
        <label for="testinput">Input</label>
        <input type="text" id="testinput" name="testinput"/>
		<?php $wp_nonces->getNonceField(); ?>
		<?php submit_button( 'speichern' ); ?>
    </form>
<br>    
	return ob_get_clean();
</code></pre>
</p>
</section>
<section>
<p>
<i>Step 4:</i> Validate<br>
to validate you have several options<br>
call <b>$wp_nonce->verify('nonceString')</b>:<br>
<code>
if ( $wp_nonces->verifyNonce($_REQUEST['_wpnonce']) ):<br>
    //store options...;<br>
endif;
</code><br>
call <b>$wp_nonce->verifyAdmin()</b><br>
<code>
if ( $wp_nonces->verifyAdmin() ):<br>
   // store options...;<br>
endif;
</code>
<br>
call <b>$wp_nonces->verifyAjax()</b> to validate Ajax Requests<br>
<code>
if ( $wp_nonces->verifyAjax() ):<br>
    //store ajax request options...;<br>
endif;
</code>
</p>
</section>
<section>
<p>
By default is used fieldName of nonce "_wpnonce" like in WordPress use.<br>
The action string is "wp-oop-nonce"
<p></p>
</section>
<section>
<p>
For more secure and customized nonce you can modify the fieldName and Action string too<br>
<code><br>
$wp_nonces->setFieldName('my-custom-name');<br>
$wp_nonces->setAction('my-custom-action');<br>
</code>
</p>
</section>
<section>
<p>
When needed you can also use more then one instance like this<br>
<code><br>
$nonces1 = new MediaStoreNet\WpNonces\WpNonces();<br>
$nonces1->setFieldName('name1');<br>
$nonces1->setAction('action1');<br>
	<br>
$nonces2 = new MediaStoreNet\WpNonces\WpNonces();<br>
$nonces2->setFieldName('name2');<br>
$nonces2->setAction('action2');<br>
</code>
</p>
</section>
<section>
<p>
To see all available propertys and methods, please visit our 
<a href="http://wpnonces.docs.media-store.net/" target="_blank">API Documentation Site</a>
</p>
</section>

## Minimum Requirements / Dependencies
* PHP ^7.0
* WordPress latest-2

When installed for development, via Composer requires:

* phpunit/phpunit (BSD-3-Clause)
* brain/monkey (MIT)
* inpsyde/php-coding-standards

## Documentation
<b>Please visit our Documentation Site</b><br>
<a href="http://wpnonces.docs.media-store.net/" target="_blank">API Documentation Site</a>

## CHANGELOG
[Link to changelog](CHANGELOG.md)
## Licence and Copyright

GPLv2+ Licence

Copyright (c) 2019 Media-Store.net
