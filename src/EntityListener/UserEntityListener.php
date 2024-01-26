<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

#[AsEntityListener(event: Events::preUpdate, entity: User::class)]
class UserEntityListener
{
    public function __construct(
        private CacheManager $cacheManager,
    ) {
    }

    public function preUpdate(User $user)
    {
        $this->cacheManager->remove($user->getAvatar());
    }
}
