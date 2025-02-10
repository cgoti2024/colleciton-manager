<?php


function nullToEmpty($string, $emptyString = '')
{
    return !is_null($string) ? $string : $emptyString;
}


function AuthId()
{
    return Auth::id();
}

function saveSetting($shop, $key, $value)
{
    \App\Models\Setting::updateOrCreate([
        'shop_id' => $shop->id,
        'key'     => $key],
        ['value' => $value,]
    );
}

function getSetting($shop, $key)
{
    return \App\Models\Setting::where([
        'shop_id' => $shop->id,
        'key'     => $key,
    ])->first();
}
