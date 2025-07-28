<?php

namespace DumpsterfirePages\RequestManager\RequestHandles;

use DumpsterfirePages\Interfaces\IRequestHandle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SecureOnlyRequestHandle implements IRequestHandle
{
    public function handle(Request $request): Request
    {
        $isSecure = $request->isSecure();

        if (!$isSecure) {
            $httpsUri = 'https://' . $request->getHttpHost() . $request->getRequestUri();

            $header = 'Location: ' . $httpsUri;
            header($header, true, 301);
        }

        return $request;
    }
}