<?php

use \Hcode\Model\User;

function formatPrice(float $vlprice){

    $valor = 0;
    if($vlprice > 0){
        $valor = number_format($vlprice ,2 , ",",".");
    }

    return $valor;

}

function checkLogin($inadmin = true){

    return User::checkLogin($inadmin);

}

function getUserName(){

    $user = User::getFromSession();

    return utf8_decode($user->getdesperson());

}