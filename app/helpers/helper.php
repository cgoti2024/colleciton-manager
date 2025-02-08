<?php


function nullToEmpty($string, $emptyString = '')
{
    return !is_null($string) ? $string : $emptyString;
}


function AuthId()
{
    return Auth::id();
}
