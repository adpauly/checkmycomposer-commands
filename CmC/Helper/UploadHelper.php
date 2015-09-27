<?php

namespace CmC\Helper;

class UploadHelper implements UploadHelperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function uploadWithCurl(array $requirements, $syncToken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://checkmycomposer.com/synchronize");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'requirements'    => serialize($requirements),
            'sync_token' => $syncToken,
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public static function uploadWithSocket(array $requirements, $syncToken)
    {
        //create the final string to be posted using implode()
        $post_string = 'requirements='.serialize($requirements).'&sync_token='.$syncToken;

        //we are going to need the length of the data string
        $data_length = strlen($post_string);

        //let's open the connection
        if (!$connection = @fsockopen('checkmycomposer.com', 80)) {
            return false;
        }

        //sending the data
        fputs($connection, "POST  /synchronize  HTTP/1.1\r\n");
        fputs($connection, "Host:  checkmycomposer.com \r\n");
        fputs($connection, "Content-Type: application/x-www-form-urlencoded\r\n");
        fputs($connection, "Content-Length: $data_length\r\n");
        fputs($connection, "Connection: close\r\n\r\n");
        fputs($connection, $post_string);

        $res = '';
        while(!feof($connection)) {
            $res .= fread($connection, 1);
        }

        fclose($connection);

        $delimiter = '[start]'; // To not display headers
        $delimiterPos = stripos($res, $delimiter);

        if ($delimiterPos === false) {
            return false;
        }

        return substr($res, $delimiterPos + strlen($delimiter), -7);
    }
}
