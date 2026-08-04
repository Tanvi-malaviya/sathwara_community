<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\EventRegistration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $events = Event::all();
        foreach ($events as $event) {
            $registrations = EventRegistration::where('event_id', $event->id)->orderBy('id', 'asc')->get();
            $seq = 1;
            foreach ($registrations as $reg) {
                $fd = $reg->form_data ?? [];
                if (empty($fd['registration_no'])) {
                    $fd['registration_no'] = $seq;
                    $reg->update(['form_data' => $fd]);
                }
                $seq++;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed for form_data payload enrichment
    }
};
