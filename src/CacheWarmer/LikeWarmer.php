<?php

namespace App\CacheWarmer;

use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class LikeWarmer implements CacheWarmerInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SettingRepository
     */
    private $settingRepository;

    public function __construct(
        AdapterInterface $cache,
        EntityManagerInterface $entityManager,
        SettingRepository $settingRepository
    ) {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * Warms up the cache.
     *
     * @return string[] A list of classes or files to preload on PHP 7.4+
     */
    public function warmUp(string $cacheDir)
    {
        $settings = $this->settingRepository->findAll();

        foreach ($settings as $setting) {
            $cacheKey = sprintf('settings.%s', $setting->getName());

            /** @var CacheItem $cacheItem */
            $cacheItem = $this->cache->getItem($cacheKey);
            $cacheItem->set($setting->getValue());

            $this->cache->save($cacheItem);
        }
    }
}
