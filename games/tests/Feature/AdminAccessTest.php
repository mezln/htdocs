<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_management_pages_require_admin_role(): void
    {
        $urls = [
            '/admin/permissions',
            '/admin/roles',
            '/admin/role-permissions',
            '/admin/user-roles',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect('/login');
        }

        $customer = User::factory()->create();
        $customer->assignRole(Role::create(['name' => 'klant', 'guard_name' => 'web']));

        foreach ($urls as $url) {
            $this->actingAs($customer)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_create_update_and_delete_permissions_and_roles(): void
    {
        $this->actingAs($this->makeAdmin());

        $this->get('/admin/permissions')->assertOk()->assertSee('Permissies beheren');
        $this->get('/admin/roles')->assertOk()->assertSee('Rollen beheren');
        $this->get('/admin/role-permissions')->assertOk()->assertSee('Permissies koppelen aan rollen');
        $this->get('/admin/user-roles')->assertOk()->assertSee('Rollen koppelen aan gebruikers');

        $this->post('/admin/permissions', ['name' => 'games bekijken'])->assertRedirect('/admin/permissions');
        $permission = Permission::where('name', 'games bekijken')->firstOrFail();
        $this->put("/admin/permissions/{$permission->id}", ['name' => 'games lezen'])->assertRedirect('/admin/permissions');
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'games lezen', 'guard_name' => 'web']);
        $this->delete("/admin/permissions/{$permission->id}")->assertRedirect('/admin/permissions');
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);

        $this->post('/admin/roles', ['name' => 'moderator'])->assertRedirect('/admin/roles');
        $role = Role::where('name', 'moderator')->firstOrFail();
        $this->put("/admin/roles/{$role->id}", ['name' => 'beheerder'])->assertRedirect('/admin/roles');
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'beheerder', 'guard_name' => 'web']);
        $this->delete("/admin/roles/{$role->id}")->assertRedirect('/admin/roles');
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_admin_can_update_role_permissions_and_user_roles(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $role = Role::create(['name' => 'klant', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'product bekijken', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $this->put("/admin/role-permissions/{$role->id}", [
            'permissions' => [$permission->id],
        ])->assertRedirect('/admin/role-permissions');
        $this->assertTrue($role->fresh()->hasPermissionTo($permission));

        $this->put("/admin/user-roles/{$user->id}", [
            'roles' => [$role->id],
        ])->assertRedirect('/admin/user-roles');
        $this->assertTrue($user->fresh()->hasRole($role));

        $this->put("/admin/user-roles/{$user->id}", ['roles' => []])
            ->assertRedirect('/admin/user-roles');
        $this->assertFalse($user->fresh()->hasRole($role));
    }

    private function makeAdmin(): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::create(['name' => 'admin', 'guard_name' => 'web']));

        return $user;
    }
}
