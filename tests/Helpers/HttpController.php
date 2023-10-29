<?php

namespace Larapress\Honeypot\Tests\Helpers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Larapress\Honeypot\Http\Middleware\HoneypotMiddleware;

class HttpController extends Controller
{
    public function __construct()
    {
        $this->middleware(HoneypotMiddleware::class)->only(['post']);
    }

    public function testView()
    {
        return view('honeypot-test::test-view');
    }

    public function post(Request $request, \Larapress\Honeypot\Honeypot $honeypot)
    {
        return $request->all();
    }

    public function redirectedTo(Request $request, \Larapress\Honeypot\Honeypot $honeypot)
    {
        return $request->input();
    }
}
