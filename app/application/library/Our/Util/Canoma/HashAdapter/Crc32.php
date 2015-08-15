<?php

namespace Our\Util\Canoma\HashAdapter;

use Our\Util\Canoma\HashAdapterInterface;
use Our\Util\Canoma\HashAdapterAbstract;

/**
 * @author Mark van der Velden <mark@dynom.nl>
 */
class Crc32 extends HashAdapterAbstract implements HashAdapterInterface
{
    /**
     * Convert the argument (a string) to a hexadecimal value, using the crc32-b algorithm.
     *
     * @param string $string
     *
     * @return string
     */
    public function hash($string)
    {
        return hash(
            'crc32b',
            $string
        );
    }
}
