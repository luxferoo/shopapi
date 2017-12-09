<?php
/**
 * Created by PhpStorm.
 * User: luxfero
 * Date: 10/19/17
 * Time: 5:51 PM
 */

namespace ShopBundle\Services;

use Symfony\Component\HttpFoundation\JsonResponse;

class MyJsonResponse extends JsonResponse
{
    const RSP_OK = 200;
    const RSP_NOK = 400;
    const RSP_EXPIRED_SESSION = 331;
    const RESOURCE_NOT_FOUND = 404;
    const FORBIDDEN = 403;
    const UNAUTHORIZED = 401;
    const INVALID_PARAM = 422;
    const MISSING_PARAM = 422;
    const UNIQUE_CONSTRAINT_VIOLATION = 409;
    CONST INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE = 503;
    protected $data;
    protected $status;

    /**
     * MyJsonResponse constructor.
     * @param int $codeResponse
     * @param null $message
     * @param null $data
     * @param bool|int $status
     */
    public function __construct($codeResponse = self::RSP_OK, $message = null, $data = null, $status = self::HTTP_OK)
    {
        $this->data = array('header'=>array('code' => $codeResponse, 'msg' => $message), 'body' => $data);
        $this->status = $status;
        parent::__construct($data = $this->data, $status = $this->status);
        return $this;
    }


}