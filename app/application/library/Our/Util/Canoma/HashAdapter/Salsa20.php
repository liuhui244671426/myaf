<?php

namespace Our\Util\Canoma\HashAdapter;

use Our\Util\Canoma\HashAdapterInterface;
use Our\Util\Canoma\HashAdapterAbstract;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
class Salsa20 extends HashAdapterAbstract implements HashAdapterInterface
{
    /**
     * Convert the argument (a string) to a hexadecimal value, using the Salsa20 algorithm.
     * @see http://cr.yp.to/snuffle.html
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string)
    {
        return hash(
            'salsa20',
            $string
        );
    }
}
