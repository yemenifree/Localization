<?php namespace Arcanedev\Localization\Tests\Utilities;

use Arcanedev\Localization\Tests\TestCase;
use Arcanedev\Localization\Utilities\Negotiator;
use Illuminate\Http\Request;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class     NegotiatorTest
 *
 * @package  Arcanedev\Localization\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NegotiatorTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Contracts\Utilities\Negotiator */
    private $negotiator;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->negotiator = app(\Arcanedev\Localization\Contracts\Utilities\Negotiator::class);
    }

    public function tearDown()
    {
        unset($this->negotiator);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Negotiator::class, $this->negotiator);
    }

    /** @test */
    public function it_can_negotiate_supported_accepted_languages_header()
    {
        $languages = [
            ['en', 'en-us, en; q=0.5'],
            ['fr', 'en; q=0.5, fr; q=1.0'],
            ['es', 'es; q=0.6, en; q=0.5, fr; q=0.5'],
        ];

        foreach ($languages as $language) {
            /** @var \Illuminate\Http\Request $request */
            $request = $this->mockRequestWithAcceptLanguage($language[1])->reveal();

            $this->assertSame($language[0], $this->negotiator->negotiate($request));
        }
    }

    /** @test */
    public function it_can_negotiate_any_accepted_languages_header()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->mockRequestWithAcceptLanguage('*')->reveal();

        $this->assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_http_accepted_languages_server()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', 'jp; q=1.0')->reveal();

        $this->assertSame('fr', $this->negotiator->negotiate($request));

        $request = $this->mockRequestWithHttpAcceptLanguage('fr;q=0.8,en;q=0.4', '*/*')->reveal();

        $this->assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_supported_remote_host_server()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.omelette-au-fromage.fr',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        $this->assertSame('fr', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_unsupported_remote_host_server()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            'http://www.sushi.jp',
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        $this->assertSame('en', $this->negotiator->negotiate($request));
    }

    /** @test */
    public function it_can_negotiate_undefined_remote_host_server()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->mockRequestWithRemoteHostServer(
            null,
            'ar;q=0.8,sv;q=0.4',
            'jp; q=1.0'
        )->reveal();

        $this->assertSame('en', $this->negotiator->negotiate($request));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Mock request.
     *
     * @return ObjectProphecy
     */
    private function mockRequest()
    {
        return $this->prophesize(Request::class);
    }

    /**
     * Mock request with accept language header.
     *
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithAcceptLanguage($acceptLanguages)
    {
        return tap($this->mockRequest(), function ($request) use ($acceptLanguages) {
            /** @var mixed $request */
            $request->header('Accept-Language')->willReturn($acceptLanguages)->shouldBeCalled();
        });
    }

    /**
     * Mock request with HTTP Accept Language server.
     *
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages)
    {
        return tap($this->mockRequestWithAcceptLanguage($acceptLanguages), function ($request) use ($httpAcceptLanguages) {
            /** @var mixed $request */
            $request->server('HTTP_ACCEPT_LANGUAGE')->willReturn($httpAcceptLanguages)->shouldBeCalled();
        });
    }

    /**
     * Mock request with REMOTE_HOST server.
     *
     * @param  string  $remoteHost
     * @param  string  $httpAcceptLanguages
     * @param  string  $acceptLanguages
     *
     * @return ObjectProphecy
     */
    private function mockRequestWithRemoteHostServer($remoteHost, $httpAcceptLanguages, $acceptLanguages)
    {
        return tap($this->mockRequestWithHttpAcceptLanguage($httpAcceptLanguages, $acceptLanguages), function ($request) use ($remoteHost) {
            /** @var mixed $request */
            $request->server('REMOTE_HOST')->willReturn($remoteHost)->shouldBeCalled();
        });
    }
}
