<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberEventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_event_registration_form_shows_newly_added_family_members(): void
    {
        Role::create(['name' => 'Member', 'guard_name' => 'web']);

        $user = User::factory()->create([
            'name' => 'Member One',
            'email' => 'member@example.com',
            'status' => 'approved',
        ]);
        $user->assignRole('Member');

        $event = Event::create([
            'title' => 'Inam Vitaran Event',
            'event_type' => 'inam_vitaran',
            'description' => 'Test event',
            'venue' => 'Test Venue',
            'date' => now()->addDay(),
            'time' => '10:00:00',
            'banner_path' => 'test-banner.jpg',
            'registration_option' => true,
            'has_registration_form' => true,
            'status' => 'published',
        ]);

        $user->familyMembers;

        $user->familyMembers()->create([
            'name' => 'John Doe',
            'relationship' => 'Son',
            'gender' => 'Male',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($user)->get(route('member.events.register_form', $event->id));

        $response->assertOk();
        $response->assertSee('John Doe');
    }

    public function test_member_event_registration_form_shows_all_family_member_options(): void
    {
        Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);

        $user = User::factory()->create([
            'name' => 'Member Two',
            'email' => 'member2@example.com',
            'status' => 'approved',
        ]);
        $user->assignRole('Member');

        $event = Event::create([
            'title' => 'Inam Vitaran Event',
            'event_type' => 'inam_vitaran',
            'description' => 'Test event',
            'venue' => 'Test Venue',
            'date' => now()->addDay(),
            'time' => '10:00:00',
            'banner_path' => 'test-banner.jpg',
            'registration_option' => true,
            'has_registration_form' => true,
            'status' => 'published',
        ]);

        foreach (range(1, 6) as $index) {
            $user->familyMembers()->create([
                'name' => "Family Member {$index}",
                'relationship' => 'Child',
                'gender' => 'Male',
                'dob' => '2010-01-0' . $index,
            ]);
        }

        $response = $this->actingAs($user)->get(route('member.events.register_form', $event->id));
        $response->assertOk();

        $html = $response->getContent();
        preg_match_all('/<option\b[^>]*>/', $html, $matches);

        // first option is the placeholder, so expect 7 options total for 6 family members
        $this->assertCount(7, $matches[0]);
    }
}
