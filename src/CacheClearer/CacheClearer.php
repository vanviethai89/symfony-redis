<?php


namespace App\CacheClearer;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class CacheClearer implements CacheClearerInterface
{
    /**
     * Clears any caches necessary.
     */
    public function clear(string $cacheDir)
    {
        echo $cacheDir; echo PHP_EOL;
        echo 'Clear redis cache'; echo PHP_EOL;
        // TODO: Implement clear() method.
    }
}
