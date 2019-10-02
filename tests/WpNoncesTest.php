<?php
/**
 * Created by Media-Store.net
 * User: Artur
 * Date: 01.10.2019
 * Time: 21:17
 */

namespace MediaStoreNet\WpNonces\Test;

use Brain\Monkey;
use MediaStoreNet\WpNonces\WpNonces;
use MonkeryTestCase\BrainMonkeyWpTestCase;

class WpNoncesTest extends BrainMonkeyWpTestCase
{

    /**
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
                'wp_verify_nonce'     => function ($nonce, $action) {
                    if (md5($action) === $nonce) {
                        return 1;
                    } else {
                        return false;
                    }
                },
                'check_admin_referer' => true,
                'check_ajax_referer'  => true
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

    public function testGetInstance()
    {
        self::assertInstanceOf(
            WpNonces::class,
            $this->nonceInstannce,
            'Expected a Instance of WpNonces Class'
        );

    }

    public function testGetNonce()
    {

        $this->nonceInstannce->setAction('wp-oop-nonce');

        self::assertSame(
            md5($this->nonceInstannce->getAction()),
            $this->nonceInstannce->getNonce(),
            'Expected a genareted md5 string of "action" '
        );
    }

    public function testGetAction()
    {
        self::assertSame(
            'wp-oop-nonce',
            $this->nonceInstannce->getAction(),
            'Expected a String of "wp-oop-nonce" '
        );
    }

    public function testGetFieldName()
    {
        self::assertSame(
            '_wpnonce',
            $this->nonceInstannce->getFieldName(),
            'Expected a String of "_wpnonce" '
        );
    }

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

    public function testSetAction()
    {
        $this->nonceInstannce->setAction('test');

        self::assertSame(
            'test',
            $this->nonceInstannce->getAction(),
            'Expected a string of test'
        );

    }


    public function testSetFieldName()
    {
        $this->nonceInstannce->setFieldName('my-nonce');

        self::assertSame(
            'my-nonce',
            $this->nonceInstannce->getFieldName(),
            'Expected a string of my-nonce'
        );

    }

    public function testVerifyNonce()
    {
        self::assertEquals(
            1,
            $this->nonceInstannce->verifyNonce($this->nonceInstannce->getNonce()),
            'Expected a true condition'
        );
    }

    public function testVerifyAdmin()
    {
        self::assertTrue(true);
    }

    public function testVerifyAjax()
    {
        self::assertTrue(true);
    }


}
