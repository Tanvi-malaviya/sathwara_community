<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Business;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventSponsor;
use App\Models\SponsorshipType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Download Membership Receipt PDF
     */
    public function downloadMembership($id)
    {
        $user = User::with('memberProfile')->findOrFail($id);
        $profile = $user->memberProfile;
        $fee = (float)($user->payment_amount ?? \App\Models\Setting::get('member_signup_fee', '1000'));
        $paymentStatus = $user->payment_status ?? 'paid';
        $paymentId = $user->payment_id;
        $receiptNo = 'RCP-MEM-' . date('Y') . '-' . sprintf('%05d', $user->id);

        $pdf = Pdf::loadView('emails.receipt_pdf.membership', [
            'user' => $user,
            'profile' => $profile,
            'receiptNo' => $receiptNo,
            'amount' => $fee,
            'paymentStatus' => $paymentStatus,
            'paymentId' => $paymentId,
        ]);

        return $pdf->download('Membership_Receipt_' . $receiptNo . '.pdf');
    }

    /**
     * Download Business Directory Registration Receipt PDF
     */
    public function downloadBusiness($id)
    {
        $business = Business::with(['user', 'category', 'area'])->findOrFail($id);
        $user = $business->user;
        $fee = (float)($business->payment_amount ?? \App\Models\Setting::get('business_registration_fee', '500'));
        $paymentStatus = $business->payment_status ?? 'paid';
        $paymentId = $business->payment_id;
        $receiptNo = 'RCP-BIZ-' . date('Y') . '-' . sprintf('%05d', $business->id);

        $pdf = Pdf::loadView('emails.receipt_pdf.business', [
            'business' => $business,
            'user' => $user,
            'receiptNo' => $receiptNo,
            'amount' => $fee,
            'paymentStatus' => $paymentStatus,
            'paymentId' => $paymentId,
        ]);

        return $pdf->download('Business_Receipt_' . $receiptNo . '.pdf');
    }

    /**
     * Download Event Pass & Payment Receipt PDF
     */
    public function downloadEventPass($id)
    {
        $registration = EventRegistration::with('event')->findOrFail($id);
        $event = $registration->event ?? Event::findOrFail($registration->event_id);
        $user = $registration->user_id ? User::find($registration->user_id) : null;
        
        $passes = [];
        if (!empty($registration->form_data['passes']) && is_array($registration->form_data['passes'])) {
            $passes = $registration->form_data['passes'];
        } else {
            $count = (int)($registration->form_data['person_count'] ?? 1);
            for ($i = 1; $i <= max(1, $count); $i++) {
                $passes[] = sprintf('%03d', $i);
            }
        }

        $personCount = count($passes);
        $amount = (float)($registration->payment_amount ?? 0);
        $paymentStatus = $registration->payment_status ?? 'paid';
        $paymentId = $registration->payment_id;
        $receiptNo = 'RCP-PASS-' . date('Y') . '-' . sprintf('%05d', $registration->id);

        $pdf = Pdf::loadView('emails.receipt_pdf.event_pass', [
            'event' => $event,
            'registration' => $registration,
            'user' => $user,
            'passes' => $passes,
            'personCount' => $personCount,
            'receiptNo' => $receiptNo,
            'amount' => $amount,
            'paymentStatus' => $paymentStatus,
            'paymentId' => $paymentId,
        ]);

        return $pdf->download('Event_Pass_Receipt_' . $receiptNo . '.pdf');
    }

    /**
     * Download Sponsorship Contribution Receipt PDF
     */
    public function downloadSponsorship($id)
    {
        $sponsor = EventSponsor::with(['event', 'sponsorshipType'])->findOrFail($id);
        $event = $sponsor->event;
        $sponsorshipType = $sponsor->sponsorshipType;
        $amount = (float)($sponsor->amount ?? 0);
        $paymentStatus = $sponsor->payment_status ?? 'received';
        $paymentId = $sponsor->payment_id;
        $receiptNo = 'RCP-SPN-' . date('Y') . '-' . sprintf('%05d', $sponsor->id);

        $pdf = Pdf::loadView('emails.receipt_pdf.sponsorship', [
            'event' => $event,
            'sponsor' => $sponsor,
            'sponsorshipType' => $sponsorshipType,
            'receiptNo' => $receiptNo,
            'amount' => $amount,
            'paymentStatus' => $paymentStatus,
            'paymentId' => $paymentId,
        ]);

        return $pdf->download('Sponsorship_Receipt_' . $receiptNo . '.pdf');
    }

    /**
     * Demo / Instant preview & download for checking receipts
     */
    public function previewDemo($type)
    {
        switch ($type) {
            case 'membership':
                $user = User::with('memberProfile')->first();
                if (!$user) {
                    $user = new User([
                        'id' => 1,
                        'name' => 'Amitbhai K. Sathwara',
                        'email' => 'amit@example.com',
                        'member_code' => 'SSAM0001',
                    ]);
                }
                $fee = (float)\App\Models\Setting::get('member_signup_fee', '1000');
                $receiptNo = 'RCP-MEM-' . date('Y') . '-' . sprintf('%05d', $user->id);
                $pdf = Pdf::loadView('emails.receipt_pdf.membership', [
                    'user' => $user,
                    'profile' => $user->memberProfile ?? (object)['phone' => '9898989898', 'city' => 'Ahmedabad', 'address' => 'Satellite Road'],
                    'receiptNo' => $receiptNo,
                    'amount' => $fee,
                    'paymentStatus' => 'paid',
                    'paymentId' => 'pay_MEM_Demo12345',
                ]);
                return $pdf->download('Membership_Receipt_' . $receiptNo . '.pdf');

            case 'business':
                $business = Business::with(['user', 'category', 'area'])->first();
                if (!$business) {
                    $business = new Business([
                        'id' => 1,
                        'business_name' => 'Sathwara Builders & Developers',
                        'owner_name' => 'Rameshbhai Sathwara',
                        'phone' => '9876543210',
                        'email' => 'business@example.com',
                        'address' => '102, Shivalik Plaza, Ahmedabad',
                        'payment_amount' => 500.00,
                    ]);
                }
                $receiptNo = 'RCP-BIZ-' . date('Y') . '-' . sprintf('%05d', $business->id);
                $pdf = Pdf::loadView('emails.receipt_pdf.business', [
                    'business' => $business,
                    'user' => $business->user,
                    'receiptNo' => $receiptNo,
                    'amount' => (float)($business->payment_amount ?? 500),
                    'paymentStatus' => 'paid',
                    'paymentId' => 'pay_BIZ_Demo67890',
                ]);
                return $pdf->download('Business_Receipt_' . $receiptNo . '.pdf');

            case 'pass':
            case 'event_pass':
                $event = Event::first();
                if (!$event) {
                    $event = new Event([
                        'id' => 1,
                        'title' => 'Annual Community Mahotsav 2026',
                        'event_date' => date('Y-m-d', strtotime('+7 days')),
                        'start_time' => '18:00:00',
                        'venue' => 'Sathwara Ground, Satellite, Ahmedabad',
                    ]);
                }
                $reg = EventRegistration::first();
                if (!$reg) {
                    $reg = new EventRegistration([
                        'id' => 101,
                        'event_id' => $event->id,
                        'form_data' => [
                            'full_name' => 'Rajeshbhai Sathwara',
                            'phone' => '9898123456',
                            'passes' => ['001', '002'],
                        ],
                        'payment_amount' => 1000.00,
                        'payment_status' => 'paid',
                        'payment_id' => 'pay_PASS_Demo999',
                    ]);
                }
                $passes = $reg->form_data['passes'] ?? ['001', '002'];
                $receiptNo = 'RCP-PASS-' . date('Y') . '-' . sprintf('%05d', $reg->id);
                $pdf = Pdf::loadView('emails.receipt_pdf.event_pass', [
                    'event' => $event,
                    'registration' => $reg,
                    'user' => User::first(),
                    'passes' => $passes,
                    'personCount' => count($passes),
                    'receiptNo' => $receiptNo,
                    'amount' => (float)($reg->payment_amount ?? 1000),
                    'paymentStatus' => 'paid',
                    'paymentId' => 'pay_PASS_Demo999',
                ]);
                return $pdf->download('Event_Pass_Receipt_' . $receiptNo . '.pdf');

            case 'sponsorship':
            case 'sponsor':
                $event = Event::first();
                if (!$event) {
                    $event = new Event([
                        'id' => 1,
                        'title' => 'Annual Community Mahotsav 2026',
                        'event_date' => date('Y-m-d', strtotime('+7 days')),
                        'start_time' => '18:00:00',
                        'venue' => 'Sathwara Ground, Satellite, Ahmedabad',
                    ]);
                }
                $sponsor = EventSponsor::with('sponsorshipType')->first();
                if (!$sponsor) {
                    $sponsor = new EventSponsor([
                        'id' => 1,
                        'name' => 'Shree Ram Enterprises',
                        'contact_person' => 'Jigneshbhai Sathwara',
                        'mobile' => '9825012345',
                        'email' => 'sponsor@example.com',
                        'city' => 'Ahmedabad',
                        'amount' => 25000.00,
                        'payment_status' => 'received',
                    ]);
                }
                $receiptNo = 'RCP-SPN-' . date('Y') . '-' . sprintf('%05d', $sponsor->id);
                $pdf = Pdf::loadView('emails.receipt_pdf.sponsorship', [
                    'event' => $event,
                    'sponsor' => $sponsor,
                    'sponsorshipType' => $sponsor->sponsorshipType,
                    'receiptNo' => $receiptNo,
                    'amount' => (float)($sponsor->amount ?? 25000),
                    'paymentStatus' => 'received',
                    'paymentId' => 'pay_SPN_Demo888',
                ]);
                return $pdf->download('Sponsorship_Receipt_' . $receiptNo . '.pdf');

            default:
                abort(404, 'Unknown receipt type');
        }
    }
}
