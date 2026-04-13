<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\UserList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_visit_the_users_page(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_owner_account_cannot_be_deleted_via_user_list(): void
    {
        $owner = User::factory()->create();
        $this->assertSame(config('app.owner_user_id', 1), $owner->id);

        $editor = User::factory()->create();

        Livewire::actingAs($editor)->test(UserList::class)
            ->call('delete', $owner->id);

        $this->assertDatabaseHas('users', ['id' => $owner->id]);
    }

    public function test_owner_active_status_cannot_be_toggled(): void
    {
        $owner = User::factory()->create();
        $editor = User::factory()->create();

        Livewire::actingAs($editor)->test(UserList::class)
            ->call('toggleActive', $owner->id);

        $owner->refresh();
        $this->assertTrue($owner->is_active);
    }

    public function test_non_owner_can_be_deactivated_and_deleted(): void
    {
        $admin = User::factory()->create();
        $member = User::factory()->create(['is_active' => true]);

        Livewire::actingAs($admin)->test(UserList::class)
            ->call('toggleActive', $member->id);

        $member->refresh();
        $this->assertFalse($member->is_active);

        Livewire::actingAs($admin)->test(UserList::class)
            ->call('delete', $member->id);

        $this->assertDatabaseMissing('users', ['id' => $member->id]);
    }

    public function test_admin_can_create_a_new_user(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)->test(UserCreate::class)
            ->set('name', 'New Editor')
            ->set('email', 'new@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->set('is_active', true)
            ->call('save')
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'is_active' => true,
        ]);
    }

    public function test_cannot_delete_own_account_from_user_list(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(UserList::class)
            ->call('delete', $user->id);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_edit_form_does_not_change_owner_is_active_state(): void
    {
        $owner = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
        ]);

        Livewire::actingAs($owner)->test(UserEdit::class, ['user' => $owner])
            ->set('name', 'Owner Updated')
            ->call('save')
            ->assertRedirect(route('admin.users.index'));

        $owner->refresh();
        $this->assertSame('Owner Updated', $owner->name);
        $this->assertTrue($owner->is_active);
    }
}
