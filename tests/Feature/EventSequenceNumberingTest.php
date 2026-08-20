<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\MemberProfile;
use App\Services\EventSequenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventSequenceNumberingTest extends TestCase
{
    use RefreshDatabase;

    protected function makeEvent(array $attributes = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Test Event',
            'event_type' => 'normal',
            'description' => 'Test Description',
            'venue' => 'Test Venue',
            'date' => now()->addDays(5)->toDateString(),
            'time' => '10:00:00',
            'banner_path' => '',
            'status' => 'published',
            'pass_fee' => 100,
            'form_fee' => 0,
            'registration_option' => true,
            'has_registration_form' => false,
        ], $attributes));
    }

    protected function makeApprovedUser(): User
    {
        $user = User::create([
            'name' => 'Ramesh Patel',
            'email' => 'ramesh_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'status' => 'approved',
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Ramesh',
            'middle_name' => 'Kumar',
            'last_name' => 'Patel',
            'phone' => '9876543210',
            'gender' => 'Male',
            'dob' => '1990-01-01',
            'address' => 'Test Address',
            'city' => 'Ahmedabad',
            'pincode' => '380001',
        ]);

        return $user;
    }

    /**
     * Test Pass numbering starts from 1 for Event A and increments sequentially
     */
    public function test_pass_numbering_starts_from_1_for_event_a_and_increments(): void
    {
        $eventA = $this->makeEvent(['title' => 'Event A']);

        $pass1 = EventSequenceService::nextPassNumber($eventA->id);
        $this->assertEquals(1, $pass1);

        $reg1 = EventRegistration::create([
            'event_id' => $eventA->id,
            'pass_number' => $pass1,
            'registration_type' => 'pass',
            'status' => 'approved',
            'form_data' => ['registration_no' => $pass1],
        ]);

        $pass2 = EventSequenceService::nextPassNumber($eventA->id);
        $this->assertEquals(2, $pass2);

        $reg2 = EventRegistration::create([
            'event_id' => $eventA->id,
            'pass_number' => $pass2,
            'registration_type' => 'pass',
            'status' => 'approved',
            'form_data' => ['registration_no' => $pass2],
        ]);

        $this->assertEquals(1, $reg1->pass_number);
        $this->assertEquals('001', $reg1->formatted_reference_number);
        $this->assertEquals(2, $reg2->pass_number);
        $this->assertEquals('002', $reg2->formatted_reference_number);
    }

    /**
     * Test Pass numbering starts from 1 independently for Event B
     */
    public function test_pass_numbering_starts_from_1_independently_for_event_b(): void
    {
        $eventA = $this->makeEvent(['title' => 'Event A']);
        $eventB = $this->makeEvent(['title' => 'Event B', 'pass_fee' => 150]);

        // Create 3 passes in Event A
        for ($i = 1; $i <= 3; $i++) {
            $num = EventSequenceService::nextPassNumber($eventA->id);
            EventRegistration::create([
                'event_id' => $eventA->id,
                'pass_number' => $num,
                'registration_type' => 'pass',
                'status' => 'approved',
            ]);
        }

        // First pass in Event B MUST start from 1
        $eventBFirstPass = EventSequenceService::nextPassNumber($eventB->id);
        $this->assertEquals(1, $eventBFirstPass);

        $regB1 = EventRegistration::create([
            'event_id' => $eventB->id,
            'pass_number' => $eventBFirstPass,
            'registration_type' => 'pass',
            'status' => 'approved',
        ]);

        $this->assertEquals(1, $regB1->pass_number);
        $this->assertEquals(3, $eventA->last_pass_no);
        $this->assertEquals(1, $eventB->last_pass_no);
    }

    /**
     * Test Inam Vitran numbering starts from 1 per event independently
     */
    public function test_inam_vitran_numbering_starts_from_1_independently(): void
    {
        $event = $this->makeEvent([
            'title' => 'Inam Vitran Event',
            'event_type' => 'inam_vitaran',
            'has_registration_form' => true,
        ]);

        $inam1 = EventSequenceService::nextInamNumber($event->id);
        $this->assertEquals(1, $inam1);

        $reg1 = EventRegistration::create([
            'event_id' => $event->id,
            'inam_number' => $inam1,
            'registration_type' => 'inam_vitran',
            'status' => 'approved',
            'form_data' => ['student_name' => 'Aarav Patel', 'registration_no' => $inam1],
        ]);

        $inam2 = EventSequenceService::nextInamNumber($event->id);
        $this->assertEquals(2, $inam2);

        $this->assertEquals(1, $reg1->inam_number);
        $this->assertEquals('001', $reg1->formatted_reference_number);
        $this->assertEquals(1, $event->last_inam_no);
    }

    /**
     * Test Yuva Melo numbering starts from 1 per event independently
     */
    public function test_yuva_melo_numbering_starts_from_1_independently(): void
    {
        $event = $this->makeEvent([
            'title' => 'Yuva Melo Event',
            'event_type' => 'yuva_melo',
            'has_registration_form' => true,
            'form_fee' => 500,
        ]);

        $yuva1 = EventSequenceService::nextYuvaMeloNumber($event->id);
        $this->assertEquals(1, $yuva1);

        $reg1 = EventRegistration::create([
            'event_id' => $event->id,
            'yuva_melo_number' => $yuva1,
            'registration_type' => 'yuva_melo',
            'status' => 'approved',
            'form_data' => ['first_name' => 'Kiran', 'surname' => 'Sathwara', 'registration_no' => $yuva1],
        ]);

        $yuva2 = EventSequenceService::nextYuvaMeloNumber($event->id);
        $this->assertEquals(2, $yuva2);

        $this->assertEquals(1, $reg1->yuva_melo_number);
        $this->assertEquals('001', $reg1->formatted_reference_number);
        $this->assertEquals(1, $event->last_yuva_melo_no);
    }

    /**
     * Test all three sequences can exist in parallel on the same event without interfering
     */
    public function test_all_three_sequences_can_exist_in_parallel_on_same_event(): void
    {
        $event = $this->makeEvent([
            'title' => 'Mega Community Gathering',
            'event_type' => 'inam_vitaran',
            'pass_fee' => 50,
            'form_fee' => 100,
            'has_registration_form' => true,
        ]);

        // Add 2 passes
        $pass1 = EventSequenceService::nextPassNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'pass_number' => $pass1, 'registration_type' => 'pass']);
        $pass2 = EventSequenceService::nextPassNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'pass_number' => $pass2, 'registration_type' => 'pass']);

        // Add 2 Inam forms
        $inam1 = EventSequenceService::nextInamNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'inam_number' => $inam1, 'registration_type' => 'inam_vitran']);
        $inam2 = EventSequenceService::nextInamNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'inam_number' => $inam2, 'registration_type' => 'inam_vitran']);

        // Add 2 Yuva Melo forms
        $yuva1 = EventSequenceService::nextYuvaMeloNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'yuva_melo_number' => $yuva1, 'registration_type' => 'yuva_melo']);
        $yuva2 = EventSequenceService::nextYuvaMeloNumber($event->id);
        EventRegistration::create(['event_id' => $event->id, 'yuva_melo_number' => $yuva2, 'registration_type' => 'yuva_melo']);

        // Assert all started from 1 and reached 2
        $this->assertEquals(1, $pass1);
        $this->assertEquals(2, $pass2);
        $this->assertEquals(1, $inam1);
        $this->assertEquals(2, $inam2);
        $this->assertEquals(1, $yuva1);
        $this->assertEquals(2, $yuva2);

        $this->assertEquals(2, $event->last_pass_no);
        $this->assertEquals(2, $event->last_inam_no);
        $this->assertEquals(2, $event->last_yuva_melo_no);
    }

    /**
     * Test composite unique constraint prevents duplicate pass_number in the same event
     */
    public function test_composite_unique_constraint_prevents_duplicate_pass_numbers_in_same_event(): void
    {
        $event = $this->makeEvent(['title' => 'Event']);

        EventRegistration::create([
            'event_id' => $event->id,
            'pass_number' => 1,
            'registration_type' => 'pass',
        ]);

        $this->expectException(\Throwable::class);

        // Attempting to insert duplicate pass_number = 1 in the SAME event must fail
        EventRegistration::create([
            'event_id' => $event->id,
            'pass_number' => 1,
            'registration_type' => 'pass',
        ]);
    }

    /**
     * Test PublicController pass registration assigns sequential pass_number
     */
    public function test_public_controller_pass_submission_assigns_pass_number(): void
    {
        $user = $this->makeApprovedUser();
        $event = $this->makeEvent(['pass_fee' => 0]);

        $response = $this->actingAs($user)->post(route('events.public_register', $event->id), [
            'full_name' => 'John Doe',
            'contact_number' => '9876543210',
            'person_count' => 1,
        ]);

        $response->assertRedirect();

        $reg = EventRegistration::where('event_id', $event->id)->first();
        $this->assertNotNull($reg);
        $this->assertEquals(1, $reg->pass_number);
        $this->assertEquals('pass', $reg->registration_type);
        $this->assertEquals(1, $reg->form_data['registration_no']);
    }

    /**
     * Test PublicController inam submission assigns sequential inam_number
     */
    public function test_public_controller_inam_submission_assigns_inam_number(): void
    {
        $user = $this->makeApprovedUser();
        $event = $this->makeEvent([
            'event_type' => 'inam_vitaran',
            'has_registration_form' => true,
            'pass_fee' => 0,
        ]);

        // First register event pass for this user
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'pass_number' => 1,
            'registration_type' => 'pass',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->post(route('events.public_register', $event->id), [
            'student_name' => 'Pooja Sathwara',
            'father_name' => 'Ramesh',
            'standard' => '10th',
            'percentage' => '88.50',
            'contact_number' => '9876543210',
        ]);

        $response->assertRedirect();

        $reg = EventRegistration::where('event_id', $event->id)
            ->where('registration_type', 'inam_vitran')
            ->first();

        $this->assertNotNull($reg);
        $this->assertEquals(1, $reg->inam_number);
        $this->assertEquals('inam_vitran', $reg->registration_type);
        $this->assertEquals(1, $reg->form_data['registration_no']);
    }
}
