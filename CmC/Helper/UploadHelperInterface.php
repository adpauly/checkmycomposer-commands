<?php

namespace CmC\Helper;

interface UploadHelperInterface
{
    /**
     * Upload requirements on www.checkmycomposer.com with cURL
     * @param  array $requirements
     * @param  string $syncToken Synchronization token (give on www.checkmycomposer.com/user/projects)
     *
     * @return string $response Server's response
     */
    public static function uploadWithCurl(array $requirements, $syncToken);

    /**
     * Upload requirements on www.checkmycomposer.com with PHP sockets
     * @param  array $requirements
     * @param  string $syncToken Synchronization token (give on www.checkmycomposer.com/user/projects)
     *
     * @return string Server's response
     */
    public static function uploadWithSocket(array $requirements, $syncToken);
}