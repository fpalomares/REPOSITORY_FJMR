<?php
/**
 * Created by PhpStorm.
 * User: franc
 * Date: 23/09/2018
 * Time: 20:39
 */

namespace app\components;

class Tools
{
    public static function urlDecodeCharacters($string) {


        $decoded = $string;

        $conversion = [
            '%E1' => 'á',
            '%E9' => 'é',
            '%ED' => 'í',
            '%F3' => 'ó',
            '%FA' => 'ú',
            '%C1' => 'Á',
            '%C9' => 'É',
            '%CD' => 'Í',
            '%D3' => 'Ó',
            '%DA' => 'Ú',
            '%FC' => 'ü',
            '%DC' => 'Ü',
            '%BA' => 'º',
            '%AA' => 'ª',
            '%7E' => '~',
            '%EB' => 'ë',
            '%CB' => 'Ë',
            '%EA' => 'ê',
            '%CA' => 'Ê',
            '%C2' => 'Â',
            '%E2' => 'â',
            '%F1' => 'ñ',
            '%D1' => 'Ñ',
            '%C0' => 'À'

        ];

        try {

            foreach ($conversion as $ascii => $char) {
                $decoded = str_replace($ascii,$char,$decoded);
            }


        } catch (\Exception $e) {
            $decoded = $string;
        }

        return urldecode($decoded);
    }


}