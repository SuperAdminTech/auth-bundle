<?php


namespace SuperAdmin\Bundle\TestUtils;

use Faker\Factory;
use Faker\Generator;

/**
 * Trait FakerTrait
 * @package SuperAdmin\Bundle\TestUtils
 */
trait FakerTrait {

    /**
     * @param string $locale
     * @return Generator
     */
    protected function getFaker($locale = Factory::DEFAULT_LOCALE): Generator {
        return Factory::create($locale);
    }
}