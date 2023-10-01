<?php

namespace App\Controller;

use App\Domain\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController {

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
     */
    function __invoke(Request $request, Response $response, array $args): Response {
        $products = $this->em->getRepository(Product::class)->findAll();
        $response->getBody()->write(json_encode($products));
        return $response->withStatus(201);
    }
}