<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

#[Entity]
#[Table(name:'Product')]
class Product {
    #[Id, Column(name: 'product_id', type: 'guid')]
    public string $id;

    #[Column(name: 'product_name', type: 'string')]
    public string $name;

    #[Column(name: 'slug', type: 'string')]
    public string $slug;

    #[Column(name: 'sku', type: 'string')]
    public string $sku;

    #[Column(name: 'list_price', type: 'decimal', precision: 2, scale: 10)]
    public float $listPrice;

    #[Column(name: 'online_price', type: 'decimal', precision: 2, scale: 10)]
    public float $onlinePrice;

    #[Column(name: 'dimension', type: 'json')]
    public array $dimension;

    #[Column(name: 'weight', type: 'decimal', precision: 3, scale: 6)]
    public float $weight;

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id):void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function setSlug(string $slug): void {
        $this->slug = $slug;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function setSku(string $sku): void {
        $this->sku = $sku;
    }

    public function getListPrice() : float {
        return $this->listPrice;
    }

    public function setListPrice(float $listPrice) : void {
        $this->listPrice = $listPrice;
    }

    public function getOnlinePrice() : float {
        return $this->onlinePrice;
    }

    public function setOnlinePrice(float $onlinePrice) : void {
        $this->onlinePrice = $onlinePrice;
    }

    public function getWeight() : float {
        return $this->weight;
    }

    public function setWeight(float $weight) : void {
        $this->weight = $weight;
    }

    public function getDimension(): array {
        return $this->dimension;
    }

    public function setDimension(array $dimension): void {
        $this->dimension = $dimension;
    }
}