<?php

namespace SuperAdmin\Utils;

use SuperAdmin\Entity\Strategy;
use ccxt\AuthenticationError;

/**
 * Interface ExchangeFactory
 * @package SuperAdmin\Utils
 */
interface ExchangeFactory {
    /**
     * @param string $exchangeName
     * @param null $credentials
     * @return mixed
     * @throws AuthenticationError
     */
    function build($exchangeName = Strategy::EXCHANGE_BITMEX, $credentials = null);
}