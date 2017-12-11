<?php

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
            return $this->container->get('lexik_jwt_authentication.encoder')->decode($token);
          
    }
}
