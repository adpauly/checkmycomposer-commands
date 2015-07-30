<?php

namespace CmC\Helper;

class UploadHelper
{
    public static function uploadWithCurl($requirements, $syncToken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://local.cmc.com/app_dev.php/synchronize"); //http://local.cmc.com/
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

    public static function uploadWithSocket($requirements, $syncToken)
    {
        //create the final string to be posted using implode()
        $post_string = 'requirements='.serialize($requirements).'&sync_token='.$syncToken;

        //we are going to need the length of the data string
        $data_length = strlen($post_string);

        //let's open the connection
        if (!$connection = @fsockopen('localhost', 80)) { //local.cmc.com
            return false;
        }

        //sending the data
        fputs($connection, "POST  /app_dev.php/synchronize  HTTP/1.1\r\n");
        fputs($connection, "Host:  local.cmc.com \r\n");
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

        return substr($res, $delimiterPos + strlen($delimiter));
    }
}