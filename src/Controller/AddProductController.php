<?php

namespace App\Controller;

use App\Domain\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

class AddProductController {

    protected ContainerInterface $container;
    protected EntityManager $em;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $this->container->get('entity');
    }

    /**
     * @throws NotSupported
     * @throws ORMException
     */
    function __invoke(Request $request, Response $response, array $args): Response {
        $data = json_decode(json_decode($request->getBody()));
        $product = $this->em->getRepository(Product::class)->findOneBy([
            'sku' => $data->sku
        ]);
        if ($product) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => "Integrity constraint violation: Duplicate SKU ($data->sku) for $data->name and $product->name."
            ]));
            return $response->withStatus(201);
        }
        $product = new Product();
        $product->setId(Uuid::uuid4());
        $product->setName($data->name);
        $product->setSku($data->sku);
        $product->setDimension([
            'width' => floatval($data->dimension->width),
            'height' => floatval($data->dimension->height),
            'depth' => floatval($data->dimension->depth),
        ]);
        $product->setSlug($data->slug);
        $product->setListPrice(floatval($data->listPrice));
        $product->setOnlinePrice(floatval($data->onlinePrice));
        $product->setWeight(floatval($data->weight));
        $this->em->persist($product);
        $this->em->flush();
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => "$data->name / $data->sku was just added."
        ]));
        return $response->withStatus(201);
    }
}