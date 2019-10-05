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
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
 */

namespace MediaStoreNet\WpNonces;


/**
 * Interface NonceInterface
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
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
     * @param string $actionurl //
     *
     * @return mixed
     */
    function getNonceUrl(string $actionurl);

    /**
     * Returns hidden input fileds for Nonce and Referrer(optional)
     * By Default would echo the fields, by setting the $echo to false
     * the fields will be returned
     *
     * @param string $action  // Action String
     * @param string $name    // Name String
     * @param bool   $referer // show the referrer-field
     *                        // by default is true
     * @param bool   $echo    // echo or return the fields
     *                        // by default will be echo
     *
     * @return string
     */
    function getNonceField(string $action, string $name, bool $referer, bool $echo);

    /**
     * Verifyer for Nonce
     *
     * @param string $nonce //
     *
     * @return mixed
     */
    function verifyNonce(string $nonce);

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
