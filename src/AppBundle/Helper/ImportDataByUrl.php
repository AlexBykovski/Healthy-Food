<?php

namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class ImportDataByUrl
{
    public function getRecipeDataByUrl(){
        $request = new Request();

        var_dump($request->cookies->get("recipeRemote"));die;
        //$r = Request::create($url, 'GET' );

        //var_dump($r->getContent());die;
    }
}