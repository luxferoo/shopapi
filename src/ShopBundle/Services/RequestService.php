<?php

namespace ShopBundle\Services;

use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 00:50
 */


class RequestService
{
    /**
     * @param Request $request
     * @param array ...$params
     * @return array
     */
    public function getParams(Request $request, &...$params){
        $missingParams = array();
        foreach ($params as $i => &$param){
            $paramParts = explode(':',$param);
            if(count($paramParts)>1){
                switch ($paramParts[0]){
                    case 'file':
                        !empty($request->files->get($paramParts[1])) ? $param = $request->files->get($paramParts[1]) : $missingParams[] = $paramParts[1];
                        break;
                    case 'header':
                        !empty($request->headers->get($paramParts[1])) ? $param = $request->headers->get($paramParts[1]) :$missingParams[] = $paramParts[1];
                        break;
                    default :
                        $missingParams[] = 'unknown prefix';
                }
            }else{
                !empty($request->get($param)) ? $param = $request->get($param) :$missingParams[] = $param;
            }
        }
        return $missingParams;
    }
}