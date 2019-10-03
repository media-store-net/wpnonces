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


/**
 * Interface NonceInterface
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  GPLv2+ https://...
 * @link     Media-Store.net
 */
interface NonceInterface
{

    /**
     * Getter for the FieldName
     *
     * @return mixed
     */
    function getFieldName();

    /**
     * Setter for FieldName
     *
     * @param string $field_name // Name of the field
     *
     * @return mixed
     */
    function setFieldName(string $field_name);

    /**
     * Getter for Action
     *
     * @return mixed
     */
    function getAction();

    /**
     * Setter for Action
     *
     * @param string $action //
     *
     * @return mixed
     */
    function setAction(string $action);

    /**
     * Getter for Nonce
     *
     * @return mixed
     */
    function getNonce();

    /**
     * Getter for URL
     *
     * @param $actionurl //
     *
     * @return mixed
     */
    function getNonceUrl($actionurl);

    /**
     * Verifyer for Nonce
     *
     * @param $nonce //
     *
     * @return mixed
     */
    function verifyNonce($nonce);

    /**
     * Verifyer to Check Admin
     *
     * @return mixed
     */
    function verifyAdmin();

    /**
     * Verifyer to Check Ajax
     *
     * @return mixed
     */
    function verifyAjax();

}
