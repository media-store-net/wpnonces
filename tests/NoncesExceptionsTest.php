<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 01.10.2019
 * Time: 21:36
 * PHP Version: ^7.1
 *
 * @category Wp_Nonces_Tests
 * @package  MediaStoreNet\WpNonce\Test
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  GPLv2+ https://...
 * @link     Media-Store.net
 */

namespace MediaStoreNet\WpNonces\Test;

use Brain\Monkey;
use MediaStoreNet\WpNonces\WpNonces;
use MonkeryTestCase\BrainMonkeyWpTestCase;

/**
 * Class NoncesExceptionsTest
 *
 * @category Wp_Nonces_Tests
 * @package  MediaStoreNet\WpNonce\Test
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  GPLv2+ https://...
 * @link     Media-Store.net
 */
class NoncesExceptionsTest extends BrainMonkeyWpTestCase
{

    /**
     * Representate a Instance of WpNonces Class
     *
     * @var WpNonces
     */
    public $nonceInstannce;

    /**
     * SetUp...
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * TearDown...
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Monkey\tearDown();
        unset($this->nonceInstannce);
    }

    /**
     * Testing if the WordPress Functions doesn't exists
     * get a Exception to user
     *
     * @return void
     */
    public function testInstanceInitException()
    {
        try {
            $this->nonceInstannce = WpNonces::getInstance();
        } catch ( \Exception $exception ) {
            self::assertInstanceOf(
                \BadMethodCallException::class,
                $exception,
                'Expected a BadMethodCallException, because the function is not exists'
            );
        }
    }

    /**
     * Testing if the Instance is succefull created
     *
     * @return void
     */
    public function testInstanceInitSucces()
    {
        $this->getNonceInstance();

        self::assertTrue(
            md5($this->nonceInstannce->getAction())
            ===
            $this->nonceInstannce->getNonce(),
            'Expected a String of nonce'
        );

    }

    /**
     * Create a dummy instance and set it into
     * $this->nonceInstannce
     *
     * @return void
     */
    public function getNonceInstance()
    {
        Monkey\Functions\stubs(
            [
                'wp_create_nonce'     => function ($action) {
                    return md5($action);
                },
                'wp_nonce_url'        => true,
                'wp_verify_nonce'     => true,
                'check_admin_referer' => true,
                'check_ajax_referer'  => true
            ]
        );

        $this->nonceInstannce = WpNonces::getInstance();
    }

}
