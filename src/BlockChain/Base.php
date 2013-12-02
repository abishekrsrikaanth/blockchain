<?php
namespace BlockChain;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Exception\BadResponseException;

class Base
{
    protected function _send($request)
    {
        try {
            $response = $request->send()->json();
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse()->json();
        } catch (GuzzleException $exception) {
            throw $exception;
        }

        return $response;
    }
} 