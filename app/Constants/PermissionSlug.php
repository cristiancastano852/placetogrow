<?php

declare(strict_types=1);

namespace App\Constants;

enum PermissionSlug: string
{
    case CATEGORIES_VIEW_ANY = 'categories.view_any';
    case CATEGORIES_VIEW = 'categories.view';
    case CATEGORIES_CREATE = 'categories.create';
    case CATEGORIES_UPDATE = 'categories.update';
    case CATEGORIES_DELETE = 'categories.delete';
    case MICROSITES_VIEW_ANY = 'microsites.view_any';
    case MICROSITES_VIEW = 'microsites.view';
    case MICROSITES_CREATE = 'microsites.create';
    case MICROSITES_UPDATE = 'microsites.update';
    case MICROSITES_DELETE = 'microsites.delete';
    case USERS_VIEW_ANY = 'users.view_any';
    case USERS_VIEW = 'users.view';
    case USERS_CREATE = 'users.create';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';
    case ROLES_VIEW_ANY = 'roles.view_any';
    case ROLES_VIEW = 'roles.view';
    case ROLES_UPDATE = 'roles.update';
    case ROLE_PERMISSION_VIEW = 'role_permission.view';
    case ROLE_PERMISSION_UPDATE = 'role_permission.update';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
