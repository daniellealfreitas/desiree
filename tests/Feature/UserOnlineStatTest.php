<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserOnlineStat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserOnlineStatTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_handles_status_changes_without_duplicate_errors()
    {
        // Create a test user
        $user = User::factory()->create([
            'status' => 'offline'
        ]);
        
        Log::info('Test started with user', ['user_id' => $user->id]);
        
        // Simulate multiple status changes in quick succession
        // This should not cause any unique constraint violations
        try {
            // First attempt - should create a new record
            UserOnlineStat::updateOnStatusChange($user->id, 'online');
            $this->assertTrue(true, 'First status change worked');
            
            // Immediate second attempt - should update existing record
            UserOnlineStat::updateOnStatusChange($user->id, 'away');
            $this->assertTrue(true, 'Second status change worked');
            
            // Immediate third attempt - should update existing record again
            UserOnlineStat::updateOnStatusChange($user->id, 'dnd');
            $this->assertTrue(true, 'Third status change worked');
            
            // Back to online
            UserOnlineStat::updateOnStatusChange($user->id, 'online');
            $this->assertTrue(true, 'Fourth status change worked');
        } catch (\Exception $e) {
            $this->fail('Error occurred: ' . $e->getMessage());
        }
        
        // Verify we only have one record for today
        $count = UserOnlineStat::where('user_id', $user->id)
            ->whereDate('date', Carbon::today()->format('Y-m-d'))
            ->count();
            
        $this->assertEquals(1, $count, 'Should have exactly one record for today');
        
        // Verify the record has the final status
        $stat = UserOnlineStat::where('user_id', $user->id)
            ->whereDate('date', Carbon::today()->format('Y-m-d'))
            ->first();
            
        $this->assertEquals('online', $stat->current_status, 'Final status should be online');
        
        Log::info('Test completed successfully');
    }
}