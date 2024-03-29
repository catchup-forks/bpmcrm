<?php

namespace App\Facades;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\User;

/**
 * Facade for our User Manager
 *
 * @package app\Facades
 * @see \app\Managers\UserManager
 *
 * @method static Paginator index(array $options)
 * @method static User update(User $user, Request $request)
 * @method static string uploadAvatar(User $user, Request $request)
 * @method static string getUrlAvatar(User $user)
 *
 */
final class UserManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'user.manager';
    }
}
