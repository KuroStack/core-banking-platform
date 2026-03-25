<?php

namespace Tests\Feature\Manager;

use App\Models\{User, Role, Branch, Customer};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkApprovalTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->branch = Branch::create(['name' => 'Main', 'code' => 'BR001', 'address' => 'Addr', 'is_active' => true]);
        $role = Role::where('name', 'Manager')->first();
        $this->manager = User::create([
            'name' => 'Manager', 'email' => 'mgr@coopbank.com',
            'password' => bcrypt('Pass@123'), 'role_id' => $role->id,
            'branch_id' => $this->branch->id, 'is_active' => true,
        ]);
    }

    private function createCustomer(int $num, string $status = 'pending'): Customer
    {
        return Customer::create([
            'customer_number' => $num, 'full_name' => "Customer {$num}",
            'gender' => 'Male', 'mobile' => "9876543{$num}",
            'residential_address' => 'Addr', 'branch_id' => $this->branch->id,
            'approval_status' => $status, 'is_member_active' => $status === 'approved',
            'created_by' => $this->manager->id,
        ]);
    }

    public function test_bulk_approve_multiple_customers(): void
    {
        $c1 = $this->createCustomer(1001);
        $c2 = $this->createCustomer(1002);
        $c3 = $this->createCustomer(1003);

        $this->actingAs($this->manager)
            ->post('/manager/customers/bulk-approve', ['customer_ids' => [$c1->id, $c2->id, $c3->id]])
            ->assertRedirect()
            ->assertSessionHas('success', '3 customer(s) approved successfully.');

        $this->assertDatabaseHas('customers', ['id' => $c1->id, 'approval_status' => 'approved']);
        $this->assertDatabaseHas('customers', ['id' => $c2->id, 'approval_status' => 'approved']);
        $this->assertDatabaseHas('customers', ['id' => $c3->id, 'approval_status' => 'approved']);
    }

    public function test_bulk_approve_skips_non_pending(): void
    {
        $pending = $this->createCustomer(1001, 'pending');
        $approved = $this->createCustomer(1002, 'approved');

        $this->actingAs($this->manager)
            ->post('/manager/customers/bulk-approve', ['customer_ids' => [$pending->id, $approved->id]])
            ->assertSessionHas('success', '1 customer(s) approved successfully.');
    }

    public function test_bulk_approve_validates_required(): void
    {
        $this->actingAs($this->manager)
            ->post('/manager/customers/bulk-approve', [])
            ->assertSessionHasErrors('customer_ids');
    }
}
