<?php
/**
 * Created by PhpStorm.
 * User: DJIBRIL
 * Date: 08/09/2017
 * Time: 10:23
 */

namespace AppBundle\Service;


class HelloRenderer
{

    public function render($text){

        return "<h3> $text </h3>";
    }
}