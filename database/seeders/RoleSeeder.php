<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn(PHP_EOL.'Creating Roles and Permissions...');

        // Define Permissions
        $permissions = [
            'payment_gateway',
            'admin_panel', 'artist_panel', 'event_panel', 'studio_panel', 'record_panel',

            'view_tickets', 'view_ticket', 'create_ticket', 'update_ticket', 'delete_ticket',
            'view_events', 'view_event', 'create_event', 'update_event', 'delete_event',

            'view_albums', 'view_album', 'create_album', 'update_album', 'delete_album',
            'view_songs', 'view_song', 'create_song', 'update_song', 'delete_song',
            'view_eps', 'view_ep', 'create_ep', 'update_ep', 'delete_ep',
            'view_podcasts', 'view_podcast', 'create_podcast', 'update_podcast', 'delete_podcast',
            'view_charts', 'view_chart', 'create_chart', 'update_chart', 'delete_charts',
            'view_playlists', 'view_playlist', 'create_playlist', 'update_playlist', 'delete_playlist',

            'view_subscriptions', 'view_subscription', 'create_subscription', 'update_subscription', 'delete_subscription',
            'view_user_subscriptions', 'view_user_subscription', 'create_user_subscription', 'update_user_subscription', 'delete_user_subscription',

            'view_users', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_roles', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_permissions', 'view_permission', 'create_permission', 'update_permission', 'delete_permission',
            'view_audit_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        // Define Roles with Permissions
        $roles = [
            'Admin' => $permissions,
            'Artist' => [
                'artist_panel',
                'view_albums', 'view_album', 'create_album', 'update_album', 'delete_album',
                'view_songs', 'view_song', 'create_song', 'update_song', 'delete_song',
                'view_eps', 'view_ep', 'create_ep', 'update_ep', 'delete_ep',
                'view_podcasts', 'view_podcast', 'create_podcast', 'update_podcast', 'delete_podcast',
            ],
            'Event' => [
                'event_panel',
                'view_tickets', 'view_ticket', 'create_ticket', 'update_ticket', 'delete_ticket',
                'view_events', 'view_event', 'create_event', 'update_event', 'delete_event',
            ],
            'Studio' => [
                'studio_panel',
                'view_tickets', 'view_ticket', 'create_ticket', 'update_ticket', 'delete_ticket',
                'view_events', 'view_event', 'create_event', 'update_event', 'delete_event',

                'view_albums', 'view_album', 'create_album', 'update_album', 'delete_album',
                'view_songs', 'view_song', 'create_song', 'update_song', 'delete_song',
                'view_eps', 'view_ep', 'create_ep', 'update_ep', 'delete_ep',
                'view_podcasts', 'view_podcast', 'create_podcast', 'update_podcast', 'delete_podcast',

                'view_users', 'view_user', 'create_user', 'update_user', 'delete_user',
            ],
            'Record' => [
                'record_panel',
                'view_tickets', 'view_ticket', 'create_ticket', 'update_ticket', 'delete_ticket',
                'view_events', 'view_event', 'create_event', 'update_event', 'delete_event',

                'view_albums', 'view_album', 'create_album', 'update_album', 'delete_album',
                'view_songs', 'view_song', 'create_song', 'update_song', 'delete_song',
                'view_eps', 'view_ep', 'create_ep', 'update_ep', 'delete_ep',
                'view_podcasts', 'view_podcast', 'create_podcast', 'update_podcast', 'delete_podcast',

                'view_users', 'view_user', 'create_user', 'update_user', 'delete_user',
            ],
            'Guest' => [
                'payment_gateway',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::query()->firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
