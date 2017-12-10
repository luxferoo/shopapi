<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 23:38
 */

namespace ShopBundle\Controller;


use ShopBundle\Services\MyJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class ShopController extends Controller
{
    /**
     * @Route("/shops", methods={"GET"})
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Shop list"
     * )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="user bearer token"
     * )
     *
     * */
    public function getShopsAction(){
        $shops = $this->get('shop.shop')->getShopsForUser($this->getUser());
        return new MyJsonResponse(MyJsonResponse::RSP_OK,null,["shops"=>$shops]);
    }

}