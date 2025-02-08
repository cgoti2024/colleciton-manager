<?php
    $shop = auth()->user();
    $shopifyApiKey = config('shopify-app.api_key') ?: '';
    $shopDomain = data_get($shop, 'name') ?: '';
    $shopHost = $shopDomain ? base64_encode("https://$shopDomain/admin") : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="shopify-api-key" content="{{$shopifyApiKey}}" />
    <script src="https://cdn.shopify.com/shopifycloud/app-bridge.js"></script>
    <title>Document</title>

    @viteReactRefresh
    @vite('resources/js/index.jsx')
</head>
<body>

    <input type="hidden" id="apiKey" value="{{ $shopifyApiKey }}">
    <input type="hidden" id="shopDomain" value="{{ $shopDomain }}">
    <input type="hidden" id="shopHost" value="{{ $shopHost }}">

    <script>
        SHOPIFY_API_KEY = "{{ $shopifyApiKey }}"
        SHOP_DOMAIN = "{{ $shopDomain }}"
        SHOP_HOST = "{{ $shopHost }}"
    </script>

</body>
</html>
