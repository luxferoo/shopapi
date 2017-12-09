<?php

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 01:06
 */

namespace ShopBundle\Controller;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;


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
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="the username must be unique"
     * )
     *  @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="password of the user"
     * )
     * @SWG\Tag(name="register_user")
     */
    public function registerUserAction(){

        die;
    }


}