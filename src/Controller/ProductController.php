<?php

namespace App\Controller;

use App\Service\ProductCacheService;
use App\Service\ElasticSearchDriver;
use App\Service\MySQLDriver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    private $elasticSearch;
    private $cacheService;
    private $mySqlDriver;
    public function __construct(ElasticSearchDriver $elasticSearch, ProductCacheService $cacheService, MySQLDriver $mySQLDriver)
    {
        $this->elasticSearch = $elasticSearch;
        $this->cacheService = $cacheService;
        $this->mySqlDriver = $mySQLDriver;
    }

    #[Route('/product/{id}', name: 'app_product_detail', methods: ['GET'])]
    public function detail(string $id): JsonResponse
    {
        // Dostavame produkt z cache
        $cachedProduct = $this->cacheService->getProductFromCache($id);
        if ($cachedProduct) {
            $this->incrementProductQueryCount($id);
            return new JsonResponse($cachedProduct);
        }

        // Dostavame produkt z ElasticSearch
        $product = $this->elasticSearch->findById($id);

        if ($product) {
            $this->cacheService->saveProductToCache($id, $product);
            $this->incrementProductQueryCount($id);
            return new JsonResponse($product);
        }

        // Dostavame produkt z MySQL
        $mysqlProduct = $this->mySqlDriver->findProduct($id);

        if ($mysqlProduct) {
            $this->cacheService->saveProductToCache($id, $mysqlProduct);
            $this->incrementProductQueryCount($id);
            return new JsonResponse($mysqlProduct);
        }

        // Nikde nenasli-404
        return new JsonResponse(['error' => 'Product not found'], 404);
    }

    public function incrementProductQueryCount(string $id): void
    {
        $filePath = __DIR__ . '/../../var/product_queries.txt';
        $counts = [];

        if (file_exists($filePath)) {
            $counts = json_decode(file_get_contents($filePath), true);
        }

        if (isset($counts[$id])) {
            $counts[$id]++;
        } else {
            $counts[$id] = 1;
        }

        file_put_contents($filePath, json_encode($counts));
    }
}
