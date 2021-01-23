<?php

namespace SuperAdmin\Bundle\TestUtils;

/**
 * Class Mock
 * @package SuperAdmin\Bundle\TestUtils
 */
class Mock {
    /** @var array */
    private $methods;

    public function __construct($methods = []) {
        $this->methods = $methods;
    }

    public function __call($name, $arguments)
    {
       $method = $this->methods[$name];
       return $method(...$arguments);
    }
}
