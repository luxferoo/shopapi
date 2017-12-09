<?php
/**
 * Created by PhpStorm.
 * User: SoufianLa
 * Date: 20/10/2017
 * Time: 09:31
 */

namespace ShopBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

class JsonWebToken
{
    function __construct(Container $container){
        $this->container = $container;
    }
    /**
     * @param array $arrayInfoToEncode
     * @return null
     */
    public function generateToken($arrayInfoToEncode = [])
    {
        $token = $this->container->get('lexik_jwt_authentication.encoder')->encode($arrayInfoToEncode);
        return $token ? "Bearer ".$token : null;
    }

    /**
     * @param $request
     * @return array|bool|false|null|string
     */
    public function extractToken($request)
    {
        try{
            $extractor = new AuthorizationHeaderTokenExtractor(
                'Bearer',
                'Authorization'
            );
            $token = $extractor->extract($request);
        }catch (\Exception $e){
            $token = null;
        }
        return $token;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function decodeToken($token)
    {
        try{
            $data = $this->container->get('lexik_jwt_authentication.encoder')->decode($token);
           // var_dump($data);
        }catch(\Exception $e){
            $data = null;
        }
        return $data;
    }
}