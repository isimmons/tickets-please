<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const CreateTicket = 'ticket:create';
    public const CreateOwnTicket = 'ticket:own:create';
    public const UpdateAnyTicket = 'ticket:any:update';
    public const ReplaceTicket = 'ticket:replace';
    public const DeleteAnyTicket = 'ticket:any:delete';

    public const UpdateOwnTicket = 'ticket:own:update';
    public const DeleteOwnTicket = 'ticket:own:delete';

    public const CreateUser = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'user:delete';

    public static function getAbilities(User $user): array
    {
        // don't assign '*'
        if($user->is_admin) {
            return [
                self::CreateTicket,
                self::UpdateAnyTicket,
                self::ReplaceTicket,
                self::DeleteAnyTicket,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateOwnTicket,
                self::UpdateOwnTicket,
                self::DeleteOwnTicket,
            ];
        }
    }
}
