<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 10/12/2017
 * Time: 00:12
 */

namespace ShopBundle\Services;


use Doctrine\ORM\EntityManager;
use ShopBundle\Entity\Shop;
use ShopBundle\Entity\User;
use ShopBundle\Entity\UserShopPreference;

class ShopService
{

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getShopsForUser(User $user){
        $userShopPrefRepo =$this->em->getRepository(UserShopPreference::class);
        $preferences =$userShopPrefRepo->findBy(["user"=>$user->getId(),"action"=>UserShopPreference::DISLIKE]);
        $disliked = [];
        $now = new \DateTime('now');
        foreach ($preferences as $preference){
            if($now->format('U') - $preference->getUpdatedAt()->format('U') < 7200)
                $disliked[] = $preference->getShop()->getId();
        }
        $shopRepo =$this->em->getRepository(Shop::class);
        $shops = $shopRepo->findAll();
        $shops1 = [];
        foreach ($shops as $key=>$shop){
            if(!in_array($shop->getId(),$disliked))
                $shops1[]= [
                    "id"=>$shop->getId(),
                    "name"=>$shop->getName(),
                    "image"=>$shop->getImage(),
                ];
        }
        return $shops1;
    }

}