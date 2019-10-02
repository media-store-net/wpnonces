<?php
/**
 * Created by Media-Store.net
 * Date: 30.09.2019
 * Time: 19:04
 * PHP Version: ^7.0
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  GPLv2+ https://...
 * @link     Media-Store.net
 */

namespace MediaStoreNet\WpNonces;


interface NonceInterface
{

    function getFieldName();

    function setFieldName(string $field_name);

    function getAction();

    function setAction(string $action);

    function getNonce();

    function getNonceUrl($actionurl);

    function verifyNonce($nonce);

    function verifyAdmin();

    function verifyAjax();

}
