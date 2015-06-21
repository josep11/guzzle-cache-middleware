<?php
/**
 * Created by IntelliJ IDEA.
 * User: Kevin
 * Date: 07.06.2015
 * Time: 15:44
 */

namespace Kevinrob\GuzzleCache;


use GuzzleHttp\Promise\Promise;
use Psr\Http\Message\ResponseInterface;

class CacheMiddleware
{
    const CONFIG_STORAGE = 'storage';

    /**
     * @param array $config
     * @return \Closure the Middleware for Guzzle HandlerStack
     */
    public static function getMiddleware(array $config = [])
    {
        if (isset($config[self::CONFIG_STORAGE])) {
            if (! $config[self::CONFIG_STORAGE] instanceof CacheStorageInterface) {
                throw new \InvalidArgumentException(
                    'Storage for cache must implement CacheStorageInterface. ' .
                    '"' . get_class($config[self::CONFIG_STORAGE]) . '" given.'
                );
            }
        } else {
            // TODO new default cache storage
        }

        return function (callable $handler) use ($config) {
            return function ($request, array $options) use ($handler, $config) {
                // TODO Add logic here for HTTP Caching
                // If cache => return new FulfilledPromise(...) with response

                /** @var Promise $promise */
                $promise = $handler($request, $options);
                return $promise->then(
                    function (ResponseInterface $response) use (&$cache) {
                        if ($response->getStatusCode() >= 500) {
                            // TODO Check if we have a stale response to serve (stale-if-error)
                            // return new FulfilledPromise(...);
                        }

                        // TODO Add logic for adding response to cache

                        return $response;
                    }
                );
            };
        };
    }

}