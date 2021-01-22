<?php

namespace SuperAdmin\Utils;

use SuperAdmin\Entity\Strategy;
use ccxt\AuthenticationError;

/**
 * Class CcxtFactory
 * @package SuperAdmin\Utils
 */
class CcxtFactory implements ExchangeFactory {

    /**
     * @param string $exchangeName
     * @param null $credentials
     * @return mixed
     * @throws AuthenticationError
     */
    public function build($exchangeName = Strategy::EXCHANGE_BITMEX, $credentials = null) {
        $exchangeClass = "\\ccxt\\$exchangeName";
        if ($credentials) {
            if (!key_exists('apiKey', $credentials) || !key_exists('secret', $credentials)) {
                throw new AuthenticationError("Empty or invalid credentials");
            }
            return new $exchangeClass([
                'apiKey' => $credentials['apiKey'],
                'secret' => $credentials['secret']
            ]);
        }
        return new $exchangeClass();
    }
}