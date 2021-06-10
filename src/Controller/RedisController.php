<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @Route("/redis")
 */
class RedisController extends AbstractController
{
    /**
     * @Route("/manual-test")
     */
    public function redis(TagAwareCacheInterface $cacheClient)
    {
//        $cacheItem = $cacheClient->getItem('settings.currency');

        $cacheClient->deleteItem('stats.products_count');
        $value = $cacheClient->get('stats.products_count', function (ItemInterface $item) {
            $item->tag(['stats', 'product']);
            dd($item->getMetadata());

            return 270;
        });

        dd($value);


        $client = RedisAdapter::createConnection(
            ''
        );

        $cache = new RedisAdapter($client);

        /** @var CacheItem $cacheItem */
        $cacheItem = $cache->getItem('settings.currency');
        dd($cacheItem->isHit());


        $productsCount = $cache->getItem('stats.products_count');
        $productsCount->set(25);

        $cache->save($productsCount);


//        $value = $cache->get('my_cache_key', function (ItemInterface $item) {
//            $item->expiresAfter(3600);
//
//            // ... do some HTTP request or heavy computations
//            $computedValue = 'van viet hai';
//
//            return $computedValue;
//        });
//
//        dd('cahed: ', $value);

        dd('Finish');
    }

    /**
     * @Route("/set-cache")
     */
    public function setCache(TagAwareCacheInterface $cache)
    {
        $review1 = $cache->get('reviews.review_20', function (ItemInterface $item) {
            $item->tag(['review']);
            $item->tag(['review_20']);
//            $item->expiresAfter(60);

            return [
                'id'   => 20,
                'name' => 'Exellent Golf Course'
            ];
        });

        $review1 = $cache->get('reviews.review_21', function (ItemInterface $item) {
            $item->tag(['review', 'review_21']);
//            $item->tag(['']);
//            $item->expiresAfter(60);

            return [
                'id'   => 21,
                'name' => 'Bad Golf Course'
            ];
        });

        dd($review1);
    }

    /**
     * @Route("/invalidate-cache-tags")
     */
    public function invalidateCacheTags(TagAwareCacheInterface $cache)
    {
        dd($cache->invalidateTags(['review_21']));
    }

    /**
     * @Route("/get-cache")
     */
    public function getCache(TagAwareCacheInterface $cache)
    {
        /** @var CacheItem $cacheItem */
        $cacheItem = $cache->getItem('reviews.review_21');

        dd('Is hit: ', $cacheItem->isHit(), 'Value:', $cacheItem->get());
    }
}
