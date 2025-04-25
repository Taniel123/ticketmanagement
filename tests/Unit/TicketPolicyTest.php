<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Policies\TicketPolicy;

class TicketPolicyTest extends TestCase
{
    private TicketPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TicketPolicy();
    }

    public function test_admin_can_update_any_ticket()
    {
        $admin = new User(['role' => 'admin']);
        $ticket = new Ticket();
        
        $this->assertTrue($this->policy->update($admin, $ticket));
    }

    public function test_support_can_update_any_ticket()
    {
        $support = new User(['role' => 'support']);
        $ticket = new Ticket();

        $this->assertTrue($this->policy->update($support, $ticket));
    }

    public function test_user_can_only_update_own_ticket()
    {
        $user = new User(['role' => 'user', 'id' => 1]);
        $ownTicket = new Ticket(['user_id' => 1]);
        $otherTicket = new Ticket(['user_id' => 2]);

        $this->assertTrue($this->policy->update($user, $ownTicket));
        $this->assertFalse($this->policy->update($user, $otherTicket));
    }
}