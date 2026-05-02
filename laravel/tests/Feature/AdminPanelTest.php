<?php

namespace Tests\Feature;

use App\Models\Duel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard_and_user_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
    }

    public function test_non_admin_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_view_duel_cancellation_requests_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $duel = Duel::factory()->create(['status' => 'peticio de cancelacio']);

        $response = $this->actingAs($admin)->get(route('admin.duels.cancellations'));

        $response->assertOk();
        $response->assertViewHas('duels');

        $ids = $response->viewData('duels')->getCollection()->pluck('id')->all();
        $this->assertContains($duel->id, $ids);
    }
}

