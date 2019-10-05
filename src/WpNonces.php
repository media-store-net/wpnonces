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
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
 */

namespace MediaStoreNet\WpNonces;

/**
 * Class WpNonces
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
 */
class WpNonces implements NonceInterface
{

    /**
     * Static Instance of this Class
     *
     * @var $_instance //Static Instance of this Class
     */
    private static $_instance;

    /**
     * Default FieldName
     *
     * @var string $field_name // By default is the field_name "_wpnonce"
     */
    protected $field_name = '_wpnonce';

    /**
     * Default Action for Nonce
     *
     * @var string $action // By default is action "wp-oop-nonce"
     */
    protected $action = 'wp-oop-nonce';

    /**
     * Generated Nonce String
     *
     * @var string $nonce //Generated Nonce String
     */
    protected $nonce;

    /**
     * Allows to modify default lifetime of the Nonce
     *
     * @var int $lifetime
     */
    protected $lifetime;

    /**
     * GetInstance is a static function to return of this Class Instance
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
    public function getNonceUrl(string $actionurl): string
    {
        return wp_nonce_url($actionurl, $this->nonce, $this->field_name);
    }

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
     * @throws \Exception
     * @see    https://codex.wordpress.org/Function_Reference/wp_nonce_field
     */
    public function getNonceField(
        string $action = '',
        string $name = '',
        bool $referer = true,
        bool $echo = true
    ) {
        if (!empty($name)) :
            $this->setFieldName($name);
        endif;
        if (!empty($action)) :
            $this->setAction($action);
        endif;

        return wp_nonce_field($this->action, $this->field_name, $referer, $echo);
    }


    /**
     * Verifyer for the Nonce String
     *
     * @param string $nonce // given nonce-string
     *
     * @see    https://codex.wordpress.org/Function_Reference/wp_verify_nonce
     * @return int|bool
     */
    public function verifyNonce(string $nonce): bool
    {
        return wp_verify_nonce($nonce, $this->action);
    }

    /**
     * Verifyer for Admin Area
     *
     * @see    https://codex.wordpress.org/Function_Reference/check_admin_referer
     * @return mixed|void
     */
    public function verifyAdmin()
    {
        return check_admin_referer($this->action, $this->field_name);
    }

    /**
     * Verifyer for Ajax Requests
     *
     * @see    https://codex.wordpress.org/check_ajax_referer
     * @return mixed|void
     */
    public function verifyAjax()
    {
        return check_ajax_referer($this->action, $this->field_name, true);
    }


    /**
     * Sets filter to custom Nonce Lifetime
     * by default is lifetime 24 hours
     * You will find more Info in the WordPress Codex - Modifying the nonce system
     *
     * @param int $hours // Time in hours
     *
     * @see    https://codex.wordpress.org/WordPress_Nonces
     * @return void
     */
    public function setNonceLifetime(int $hours = 0)
    {
        if ($hours <= 0) :
            return;
        endif;

        $this->lifetime = intval($hours) * HOUR_IN_SECONDS;

        add_filter(
            'nonce_life',
            function () {
                return $this->lifetime;
            }
        );
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
            'wp_nonce_field',
            'wp_verify_nonce',
            'check_admin_referer',
            'check_ajax_referer'
        ];

        // return a Exception if not
        if (!function_exists('add_action')) :

            return $this->throwError(
                \BadMethodCallException::class,
                sprintf(
                    'The function "%s" is not defined, make sure you use this package on WordPress',
                    'add_action'
                )
            );


        elseif (function_exists('add_action')) :

            // return a admin_notice if not options page
            foreach ( $wp_functions as $function ) {
                if (!function_exists($function)) :
                    return $this->adminNotice(
                        __(
                            sprintf(
                                'It looks like that function %s doesn`t exists. 
                            Make sure you use the WpNonces Instance on a options/settings page',
                                $function
                            ),
                            'wpNonce'
                        )
                    );
                endif;
            }
        endif;

        $this->nonce = wp_create_nonce($this->action);
    }

    /**
     * This function fire a action-hook to admin notices
     * Default type is "error"
     *
     * @param string $message // Message to hook to admin_notices
     * @param string $type    // available types are
     *                        // ['success', 'info', 'warning', 'error']
     *
     * @return string
     * @see    https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
     */
    protected function adminNotice(string $message, string $type = 'error')
    {
        $class = sprintf('notice notice-%s is-dismissible', $type);

        $out = sprintf(
            '<div class="%1$s"><p>%2$s</p></div>',
            esc_attr($class),
            esc_html($message)
        );

        return add_action('admin_notices', $out);
    }

    /**
     * Throw a Error-Message if not in WordPress
     *
     * @param string $exceptionClass // Error Class to throw
     * @param string $message        // Message of the Error
     *
     * @return string
     * @throws \Exception
     */
    protected function throwError($exceptionClass, $message)
    {
        throw new $exceptionClass($message);
    }

}
