<?php
/**
 * Created by Media-Store.net
 * Date: 30.09.2019
 * Time: 19:01
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
 * Class WpNonces
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  GPLv2+ https://...
 * @link     Media-Store.net
 */
class WpNonces implements NonceInterface
{

    /**
     * Static Instance of this Class
     *
     * @var $_instance
     */
    private static $_instance;

    /**
     * Default FieldName
     *
     * @var string $field_name
     */
    protected $field_name = '_wpnonce';

    /**
     * Default Action for Nonce
     *
     * @var string $action
     */
    protected $action = 'wp-oop-nonce';

    /**
     * Generated Nonce String
     *
     * @var string $nonce
     */
    protected $nonce;

    /**
     * GetInstance is a static function o return of this Class Instance
     *
     * @return WpNonces
     */
    public static function getInstance()
    {
        // Initialize the service if it's not already set.
        if (self::$_instance === null) {
            self::$_instance = new WpNonces();
        }

        // Return the instance.
        return self::$_instance;
    }

    /**
     * WpNonces constructor.
     */
    public function __construct()
    {
        $this->init();
    }


    /**
     * Getter for FieldName
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->field_name;
    }


    /**
     * Setter for FieldName
     *
     * @param string $field_name //
     *
     * @return void
     */
    public function setFieldName(string $field_name)
    {
        $this->field_name = $field_name;
    }


    /**
     * Getter for Nonce String
     *
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * Getter for Action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Setter for Action
     *
     * @param string $action //
     *
     * @return void
     * @throws \Exception
     */
    public function setAction(string $action)
    {
        if ($action !== $this->action) :
            $this->action = $action;
            $this->init();
        endif;
    }

    /**
     * Getter for NonceUrl String
     *
     * @param string $actionurl //
     *
     * @see    https://codex.wordpress.org/Function_Reference/wp_nonce_url
     * @return string
     */
    public function getNonceUrl($actionurl): string
    {
        return wp_nonce_url($actionurl, $this->nonce, $this->field_name);
    }


    /**
     * Verifyer for the Nonce String
     *
     * @param string $nonce // given nonce-string
     *
     * @see    https://codex.wordpress.org/Function_Reference/wp_verify_nonce
     * @return int|bool
     */
    public function verifyNonce($nonce): bool
    {
        return wp_verify_nonce($nonce, $this->action);
    }

    /**
     * Verifyer for Admin Area
     *
     * @return mixed|void
     */
    public function verifyAdmin()
    {
        // TODO: Implement verifyAdmin() method.
    }

    /**
     * Verifyer for Ajax Requests
     *
     * @return mixed|void
     */
    public function verifyAjax()
    {
        // TODO: Implement verifyAjax() method.
    }

    /**
     * Initialisation / create of the Nonce
     *
     * @see    https://codex.wordpress.org/Function_Reference/wp_create_nonce
     * @return string|void
     * @throws \Exception
     */
    protected function init()
    {
        // make sure that's WordPress
        $wp_functions = [
            'wp_create_nonce',
            'wp_nonce_url',
            'wp_verify_nonce',
            'check_admin_referer',
            'check_ajax_referer'
        ];
        // return a Exception if not
        foreach ( $wp_functions as $function ) {
            if (!function_exists($function)) :
                $this->throwError(
                    \BadMethodCallException::class,
                    sprintf(
                        'the function "%s" is not defined, make sure you use this package on WordPress',
                        $function
                    )
                );

                return;

            endif;
        }

        $this->nonce = wp_create_nonce($this->action);
    }

    /**
     * Throw a Error-Message if not in WordPress
     *
     * @param string $exceptionClass // Error Class to throw
     * @param string $message        // Message of the Error
     *
     * @return void
     * @throws \Exception
     */
    protected function throwError($exceptionClass, $message)
    {
        throw new $exceptionClass($message);
    }

}
