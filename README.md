# WpNonces README

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

In general, you can use this class without further adjustment.

As an example I have created a plugin, where i will use this package to handle nonce.


_Step 1 :_ require the autoload.php to make it accessible in your plugin/theme


- download the Repo and store it in vendor folder in your plugin<br>
<code>
require_once WP_PLUGIN_DIR . '/' . plugin_basename( __DIR__ ) . '/vendor/WpNonces/vendor/autoload.php';
</code> 


_Step 2 :_ load an instance in your settings or options page

<code>$wp_nonces = \MediaStoreNet\WpNonces\WpNonces::getInstance();</code><br>
<b>this static method allows you to use allways the same instance of the class<br>
in all your settings/options files.</b>


_Step 3:_ create a form
In my case i do this on a separate function and call these in my settings/options page

<?php
function my_form( $wp_nonces ) {
	ob_start();
?>

    <form method="get" action="options.php">
        <label for="testinput">Input</label>
        <input type="text" id="testinput" name="testinput"/>
		<?php $wp_nonces->getNonceField(); ?>
		<?php submit_button( 'speichern' ); ?>
    </form>
<?php 
	return ob_get_clean();
?>

_Step 4:_ Validate
to validate you have several options

call **$wp_nonce->verify('nonceString')**
<code>
if ( $wp_nonces->verifyNonce($_REQUEST['_wpnonce']) ):
    //store options...;
endif;
</code>

call **$wp_nonce->verifyAdmin()**
<code>
if ( $wp_nonces->verifyAdmin() ):
   // store options...;
endif;
</code>

call **$wp_nonces->verifyAjax()** to validate Ajax Requests
<code>
if ( $wp_nonces->verifyAjax() ):
    //store ajax request options...;
endif;
</code>


By default is used fieldName of nonce "_wpnonce" like in WordPress use.
The action string is "wp-oop-nonce"


For more secure and customized nonce you can modify the fieldName and Action string too<br>
<code><br>
$wp_nonces->setFieldName('my-custom-name');<br>
$wp_nonces->setAction('my-custom-action');<br>
</code>

<section>
<p>
When needed you can also use more then one instance like this<br>
<code>
$nonces1 = new MediaStoreNet\WpNonces\WpNonces();
$nonces1->setFieldName('name1');
$nonces1->setAction('action1');
	<br>
$nonces2 = new MediaStoreNet\WpNonces\WpNonces();
$nonces2->setFieldName('name2');
$nonces2->setAction('action2');
</code>


To see all available propertys and methods, please visit our 
[API Documentation Site](http://wpnonces.docs.media-store.net/)


## Minimum Requirements / Dependencies
* PHP ^7.0
* WordPress latest-2

When installed for development, via Composer requires:

* phpunit/phpunit (BSD-3-Clause)
* brain/monkey (MIT)
* inpsyde/php-coding-standards

## Documentation
**Please visit our Documentation Site**
[API Documentation Site](http://wpnonces.docs.media-store.net/)

## CHANGELOG
[Link to changelog](CHANGELOG.md)
## Licence and Copyright

GPLv2+ Licence

Copyright (c) 2019 Media-Store.net
