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

class WpNonces implements NonceInterface
{

    private static $_instance;

    protected $field_name = '_wpnonce';

    protected $action = 'wp-oop-nonce';

    protected $nonce;

    public static function getInstance()
    {
        // Initialize the service if it's not already set.
        if (self::$_instance === null) {
            self::$_instance = new WpNonces();
        }

        // Return the instance.
        return self::$_instance;
    }

    public function __construct()
    {
        $this->init();
    }

    /**
     * @return mixed
     */
    public function getFieldName(): string
    {
        return $this->field_name;
    }

    /**
     * @param mixed $field_name
     */
    public function setFieldName(string $field_name)
    {
        $this->field_name = $field_name;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        if ($action !== $this->action) :
            $this->action = $action;
            $this->init();
        endif;
    }

    public function getNonceUrl($actionurl): string
    {
        return wp_nonce_url($actionurl, $this->nonce, $this->field_name);
    }


    public function verifyNonce($nonce): bool
    {
        return wp_verify_nonce($nonce, $this->action);
    }

    public function verifyAdmin()
    {
        // TODO: Implement verifyAdmin() method.
    }

    public function verifyAjax()
    {
        // TODO: Implement verifyAjax() method.
    }

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
                return $this->throwError(
                    \BadMethodCallException::class,
                    sprintf(
                        'the function "%s" is not defined, make sure you use this package on WordPress',
                        $function
                    )
                );
            endif;
        }

        $this->nonce = wp_create_nonce($this->action);
    }

    protected function throwError($exceptionClass, $message)
    {
        throw new $exceptionClass($message);
    }

}
