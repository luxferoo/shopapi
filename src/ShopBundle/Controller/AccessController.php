<?php

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 01:06
 */

namespace ShopBundle\Controller;

use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ShopBundle\Services\MyJsonResponse;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;



/**
 * @Route("/authorized")
 */

class AccessController extends Controller
{

    /**
     * @Route("/register", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="User registered"
     * )
     * @SWG\Response(
     *     response=422,
     *     description="missing param or invalid password"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="unique constraint violation"
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="the username must be unique"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="password of the user"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="the email must be unique"
     * )
     * @SWG\Tag(name="register_user")
     * @param Request $request
     * @return MyJsonResponse
     */
    public function registerUserAction(Request $request){
        /* List of expected parameters .
         * available prefixes [file,header]
         * getParams(Request $request, &...$params)
         * returns an array with missing params or empty array
        */
        $username = 'username' ;
        $password = 'password' ;
        $email = 'email';
        if($missingParams = $this->get('shop.request')->getParams($request,$password,$email,$username))
            return  new MyJsonResponse( MyJsonResponse::MISSING_PARAM,["missing params "=>$missingParams]);
        if($res = $this->get('shop.user')->checkParams($password,$email,$username))
            return  new MyJsonResponse( MyJsonResponse::INVALID_PARAM,$res);
        $BearerToken = $this->get('shop.user')->registerUser($password,$email,$username);
        return  new MyJsonResponse(MyJsonResponse::RSP_OK,"success",["token"=>$BearerToken]);
    }

    /**
     * @SWG\Tag(name="SSO")
     * @SWG\Response(
     *     response=200,
     *     description="successful authentication"
     * )
     *  @SWG\Response(
     *     response=422,
     *     description="Missing param"
     * )
     *  @SWG\Response(
     *     response=401,
     *     description="Unauthorized"
     * )
     ** @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="the username must be unique"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="password of the user"
     * )
     * @param Request $request
     * @Route("/login", methods={"POST"})
     *
     * @return MyJsonResponse
     */
    public function ssoAction(Request $request){
        $username = 'username' ;
        $password = 'password' ;
        if($missingParams = $this->get('shop.request')->getParams($request,$password,$username))
            return  new MyJsonResponse( MyJsonResponse::MISSING_PARAM,["missing params "=>$missingParams]);
        if(!$userId = $this->get('shop.user')->getUserId($username,$password))
            return  new MyJsonResponse( MyJsonResponse::UNAUTHORIZED,"bad credentials");
        $jwtService = $this->get('shop.jwt');
        $BearerToken =$jwtService->generateToken(['id' => $userId]);
        return  new MyJsonResponse(MyJsonResponse::RSP_OK,"success",["token"=>$BearerToken]);
    }


}