<?php

declare(strict_types=1);

namespace App\Constants;

final class PermissionSlug
{
    public const  CATEGORIES_VIEW_ANY = 'categories.view_any';
    public const  CATEGORIES_VIEW = 'categories.view';
    public const  CATEGORIES_CREATE = 'categories.create';
    public const  CATEGORIES_UPDATE = 'categories.update';
    public const  CATEGORIES_DELETE = 'categories.delete';

    public const  MICROSITES_VIEW_ANY = 'microsites.view_any';
    public const  MICROSITES_VIEW = 'microsites.view';
    public const  MICROSITES_CREATE = 'microsites.create';
    public const  MICROSITES_UPDATE = 'microsites.update';
    public const  MICROSITES_DELETE = 'microsites.delete';

    public const  USERS_VIEW_ANY = 'users.view_any';
    public const  USERS_VIEW = 'users.view';
    public const  USERS_CREATE = 'users.create';
    public const  USERS_UPDATE = 'users.update';
    public const  USERS_DELETE = 'users.delete';

    public const  ROLES_VIEW_ANY = 'roles.view_any';
    public const  ROLES_VIEW = 'roles.view';
    public const  ROLES_UPDATE = 'roles.update';

    public const ROLE_PERMISSION_VIEW = 'role_permission.view';
    public const ROLE_PERMISSION_UPDATE = 'role_permission.update';


    public static function toArray(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
