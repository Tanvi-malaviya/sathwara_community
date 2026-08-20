<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\EventRegistration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('pass_number')->nullable()->after('event_id');
            $table->unsignedBigInteger('inam_number')->nullable()->after('pass_number');
            $table->unsignedBigInteger('yuva_melo_number')->nullable()->after('inam_number');
            $table->string('registration_type', 30)->default('pass')->after('yuva_melo_number');

            $table->index('registration_type');
            $table->unique(['event_id', 'pass_number'], 'uq_event_pass_number');
            $table->unique(['event_id', 'inam_number'], 'uq_event_inam_number');
            $table->unique(['event_id', 'yuva_melo_number'], 'uq_event_yuva_melo_number');
        });

        // Backfill existing data sequentially per event
        $events = Event::withTrashed()->get();
        foreach ($events as $event) {
            $allRegistrations = EventRegistration::where('event_id', $event->id)
                ->orderBy('id', 'asc')
                ->get();

            $passSeq = 1;
            $inamSeq = 1;
            $yuvaSeq = 1;

            foreach ($allRegistrations as $reg) {
                $fd = $reg->form_data ?? [];

                $isInamForm = !empty($fd['student_name']);
                $isYuvaForm = !empty($fd['surname']) || !empty($fd['first_name']) || !empty($fd['qualification']) || !empty($fd['birth_date']);

                if ($event->event_type === 'inam_vitaran' && $isInamForm) {
                    $reg->inam_number = $inamSeq;
                    $reg->registration_type = 'inam_vitran';
                    $fd['registration_no'] = $inamSeq;
                    $reg->form_data = $fd;
                    $reg->save();
                    $inamSeq++;
                } elseif ($event->event_type === 'yuva_melo' && $isYuvaForm) {
                    $reg->yuva_melo_number = $yuvaSeq;
                    $reg->registration_type = 'yuva_melo';
                    $fd['registration_no'] = $yuvaSeq;
                    $reg->form_data = $fd;
                    $reg->save();
                    $yuvaSeq++;
                } else {
                    $reg->pass_number = $passSeq;
                    $reg->registration_type = 'pass';
                    $fd['registration_no'] = $passSeq;
                    $reg->form_data = $fd;
                    $reg->save();
                    $passSeq++;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropUnique('uq_event_pass_number');
            $table->dropUnique('uq_event_inam_number');
            $table->dropUnique('uq_event_yuva_melo_number');
            $table->dropIndex(['registration_type']);

            $table->dropColumn([
                'pass_number',
                'inam_number',
                'yuva_melo_number',
                'registration_type',
            ]);
        });
    }
};
