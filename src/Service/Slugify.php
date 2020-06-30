<?php
/**
 * Auteur: Khaled Benharrat
 * Date: 03/06/2020
 */

//src/Service/Slugify.php
namespace App\Service;

class Slugify
{
    protected $input;

    /**
    * Generates a slug with a string
    *
    * @param string $input
    */
    public function generate(string $input) :string
    {
        // conversion of special characters to unicode characters
        if (!empty($input)) {
            $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        }
        // return a slug without punctuation, without spaces at the beginning and end of the chain,
        // without successive "-" and in lowercase
        if (!empty($slug)) {
            $slug = trim($slug);
        }
        if (!empty($slug)) {
            $slug = preg_replace("/[[:punct:]]/", "", $slug);
        }
        if (!empty($slug)) {
            $slug = preg_replace("/ +/", "-", $slug);
        }
        if (!empty($slug)) {
            $slug = preg_replace("/-+/", "-", $slug);
        }
        if (!empty($slug)) {
            $slug = strtolower($slug);
            return $slug;
        }
    }
}
