<?php

namespace App\EntityListener;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Product::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Product::class)]
class ProductEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    // Set slug on create product, Doctrine Entity Listener
    public function prePersist(Product $product)
    {
        // $product->computeSlug($this->slugger);
    }

    public function preUpdate(Product $product)
    {
        // $product->computeSlug($this->slugger);
    }
}
