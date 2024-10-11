<?php

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductCacheService
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    // Get product
    public function getProductFromCache(string $id)
    {
        return $this->cache->get("product_{$id}", function (ItemInterface $item) {
            return null;
        });
    }

    // Save product
    public function saveProductToCache(string $id, array $product)
    {
        return $this->cache->delete("product_{$id}") &&
            $this->cache->get("product_{$id}", function (ItemInterface $item) use ($product) {
                $item->set($product);
                $item->expiresAfter(3600);
                return $product;
            });
    }
}
