<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 23:38
 */

namespace ShopBundle\Controller;


use ShopBundle\Entity\Shop;
use ShopBundle\Entity\UserShopPreference;
use ShopBundle\Utilities\MyJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function getShopsAction(Request $request){
        $shops = $this->get('shop.shop')->getShopsForUser($this->getUser());
        return new MyJsonResponse(MyJsonResponse::RSP_OK,null,["shops"=>$shops]);
    }

    /**
     * @Route("/preference/{shopId}", methods={"PUT"})
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Action done"
     * )
     *  @SWG\Response(
     *     response=422,
     *     description="missing parameter | invalid value for action"
     * )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="user bearer token"
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     type="string",
     *     description="LIKE | DISLIKE"
     * )
     * @param Request $request
     * @return MyJsonResponse
     */

    public function patchPreferenceAction(Request $request){
        $serviceTranslator = $this->container->get('translator');
        $shopId ='shopId';
        $action ='action';
        if($missingParams = $this->get('shop.request')->getParams($request,$shopId,$action))
            return  new MyJsonResponse( MyJsonResponse::MISSING_PARAM,["missing params "=>$missingParams]);
        if($action != UserShopPreference::LIKE && $action != UserShopPreference::DISLIKE)
            return  new MyJsonResponse( MyJsonResponse::INVALID_PARAM,
                $serviceTranslator->trans("preference.unknown_action",[],"messages"));
        if(!$this->get('shop.shop')->updateShopPreference($this->getUser(),$shopId,$action))
            return new MyJsonResponse(MyJsonResponse::RESOURCE_NOT_FOUND,"no shop found with this id");
        return new MyJsonResponse(MyJsonResponse::RSP_OK,
            $serviceTranslator->trans("preference.preference_state_changed",[],"messages"));
    }

    /**
     * @Route("/liked-shops", methods={"GET"})
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Liked shops list"
     * )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="user bearer token"
     * )
     *
     * */
    public function getLikedShopsAction(Request $request){
        $shops = $this->get('shop.shop')->getLikedShopsForUser($this->getUser());
        return new MyJsonResponse(MyJsonResponse::RSP_OK,null,["shops"=>$shops]);
    }
}