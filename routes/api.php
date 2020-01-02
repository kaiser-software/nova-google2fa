<?php

use Illuminate\Support\Facades\Route;

/**
 * This route is called when user must first time confirm secret
 */
Route::post('nova/register', 'Lifeonscreen\Google2fa\Google2fa@register');

/**
 * This route is called when user must first time confirm secret
 */
Route::post('nova/confirm', 'Lifeonscreen\Google2fa\Google2fa@confirm');

/**
 * This route is called to verify users secret
 */
Route::post('nova/authenticate', 'Lifeonscreen\Google2fa\Google2fa@authenticate');
