<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 01.10.2019
 * Time: 21:17
 * PHP Version: ^7.1
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce\Test
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
 */

namespace MediaStoreNet\WpNonces\Test;

use Brain\Monkey;
use MediaStoreNet\WpNonces\WpNonces;
use MonkeryTestCase\BrainMonkeyWpTestCase;

/**
 * Class WpNoncesTest
 *
 * @category Wp_Nonces
 * @package  MediaStoreNet\WpNonce\Test
 * @author   Artur Voll <info@pcservice-voll.de>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     Media-Store.net <https://media-store.net>
 */
class WpNoncesTest extends BrainMonkeyWpTestCase
{

    /**
     * Instance of WpNonces Class
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

        // define wp_functions fakes
        Monkey\Functions\stubs(
            [
                'wp_create_nonce'     => function ($action) {
                    return md5($action);
                },
                'wp_nonce_url'        => function ($actionurl, $nonce, $name) {
                    return sprintf(
                        '%s?%s=%s',
                        $actionurl,
                        $name,
                        $nonce
                    );
                },
                'wp_nonce_field'      => function (
                    $action,
                    $name,
                    $referer = true,
                    $echo = true
                ) {
                    $field1 = sprintf(
                        '<input type="hidden" %1$s="%2$s" />',
                        $name,
                        $action
                    );
                    $field2 = sprintf(
                        '<input type="hidden" %1$s="%2$s" />'
                    );

                    $output = $field1;
                    if ($referer) :
                        $output .= '<br>' . $field2;
                    endif;

                    if ($echo) :
                        echo $output;
                    else:
                        return $output;
                    endif;
                },
                'wp_verify_nonce'     => function ($nonce, $action) {
                    if (md5($action) === $nonce) {
                        return 1;
                    } else {
                        return false;
                    }
                },
                'check_admin_referer' => function ($action, $query_arg) {
                    if (isset($_REQUEST[$query_arg])
                        && $_REQUEST[$query_arg] === md5($action)
                    ) {
                        return true;
                    } else {
                        return 'not';
                    }
                },
                'check_ajax_referer'  => function ($action, $query_arg, $die) {
                    if (isset($_REQUEST[$query_arg])
                        && $_REQUEST[$query_arg] === md5($action)
                    ) {
                        return true;
                    } else {
                        return $die ? 'not' : false;
                    }
                }
            ]
        );

        $this->nonceInstannce = WpNonces::getInstance();
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
     * Testing static getInstances method
     *
     * @return string
     */
    public function testGetInstance()
    {
        self::assertInstanceOf(
            WpNonces::class,
            $this->nonceInstannce,
            'Expected a Instance of WpNonces Class'
        );

    }

    /**
     * Testing give an correct NonceString
     *
     * @return string
     * @throws \Exception
     */
    public function testGetNonce()
    {

        $this->nonceInstannce->setAction('wp-oop-nonce');

        self::assertSame(
            md5($this->nonceInstannce->getAction()),
            $this->nonceInstannce->getNonce(),
            'Expected a genareted md5 string of "action" '
        );
    }

    /**
     * Testing get an correct action string
     *
     * @return string
     */
    public function testGetAction()
    {
        self::assertSame(
            'wp-oop-nonce',
            $this->nonceInstannce->getAction(),
            'Expected a String of "wp-oop-nonce" '
        );
    }

    /**
     * Testing get an correct fieldName
     *
     * @return string
     */
    public function testGetFieldName()
    {
        self::assertSame(
            '_wpnonce',
            $this->nonceInstannce->getFieldName(),
            'Expected a String of "_wpnonce" '
        );
    }

    /**
     * Testing get an correct NonceUrl String
     *
     * @return string
     */
    public function testGetNonceUrl()
    {
        $url      = $this->nonceInstannce->getNonceUrl('http://test.de');
        $expected = 'http://test.de?' .
                    $this->nonceInstannce->getFieldName() . '=' .
                    $this->nonceInstannce->getNonce();

        self::assertSame(
            $expected,
            $url,
            'Expected a string of url with integrated nonce'
        );
    }

    /**
     * Testing set the action to string test
     *
     * @return string
     * @throws \Exception
     */
    public function testSetAction()
    {
        $this->nonceInstannce->setAction('test');

        self::assertSame(
            'test',
            $this->nonceInstannce->getAction(),
            'Expected a string of test'
        );

    }


    /**
     * Testing set the fieldName to my-nonce string
     *
     * @return string
     */
    public function testSetFieldName()
    {
        $this->nonceInstannce->setFieldName('my-nonce');

        self::assertSame(
            'my-nonce',
            $this->nonceInstannce->getFieldName(),
            'Expected a string of my-nonce'
        );

    }

    /**
     * Testing verifying of Nonce String
     *
     * @return string
     */
    public function testVerifyNonce()
    {
        self::assertEquals(
            1,
            $this->nonceInstannce->verifyNonce($this->nonceInstannce->getNonce()),
            'Expected a true condition'
        );
    }

    /**
     * Testing verifying check_admin_referer
     *
     * @return string
     */
    public function testVerifyAdmin()
    {
        $nonceName = $this->nonceInstannce->getFieldName();
        // set $_REQUEST
        $_REQUEST[$nonceName] = $this->nonceInstannce->getNonce();

        self::assertTrue(
            isset($_REQUEST[$nonceName]),
            'Expected that the $_REQUEST of field_name is setted'
        );

        self::assertEquals(
            md5($this->nonceInstannce->getAction()),
            $_REQUEST[$nonceName],
            'Expected that the $_REQUEST nonce is the same as md5() of action'
        );

        self::assertTrue(
            $this->nonceInstannce->verifyAdmin(),
            'Expected that verifyAdmin is truely'
        );

        // set $_REQUEST to wrong nonce
        $_REQUEST[$nonceName] = 'abcde';
        self::assertSame(
            'not',
            $this->nonceInstannce->verifyAdmin(),
            'Expected is a die() with "not" string'
        );
    }

    /**
     * Testing verifying Ajax Request
     *
     * @return string
     */
    public function testVerifyAjax()
    {
        $nonceName = $this->nonceInstannce->getFieldName();
        // set $_REQUEST
        $_REQUEST[$nonceName] = $this->nonceInstannce->getNonce();

        self::assertTrue(
            isset($_REQUEST[$nonceName]),
            'Expected that the $_REQUEST of field_name is setted'
        );

        self::assertEquals(
            md5($this->nonceInstannce->getAction()),
            $_REQUEST[$nonceName],
            'Expected that the $_REQUEST nonce is the same as md5() of action'
        );

        self::assertTrue(
            $this->nonceInstannce->verifyAjax(),
            'Expected that verifyAdmin is truely'
        );

        // set $_REQUEST to wrong nonce
        $_REQUEST[$nonceName] = 'abcde';
        self::assertSame(
            'not',
            $this->nonceInstannce->verifyAjax(),
            'Expected is a die() with "not" string'
        );
    }


}
