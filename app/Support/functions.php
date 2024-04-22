<?php

if (!function_exists('user')) {

    function user(): ?\App\Models\User
    {
        return auth()->user();
    }
}
