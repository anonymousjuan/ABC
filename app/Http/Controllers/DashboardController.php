<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Encounter;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $clinicId = $user->clinic_id;

        // Do not display another clinic's records.
        if (!$clinicId) {
            return view('dashboard', [
                'setupRequired' => true,
                'totalPatients' => 0,
                'todayAppointments' => 0,
                'totalEncounters' => 0,
                'recentPatients' => collect(),
            ]);
        }

        $totalPatients = Patient::where('clinic_id', $clinicId)
            ->count();

        $todayAppointments = Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->count();

        $totalEncounters = Encounter::where('clinic_id', $clinicId)
            ->count();

        $recentPatients = Patient::where('clinic_id', $clinicId)
            ->latest()
            ->take(5)
            ->get();
        $todayPatients = Patient::where('clinic_id', $clinicId)
        ->whereDate('created_at', today())
        ->count();

        $todayEncounters = Encounter::where('clinic_id', $clinicId)
        ->whereDate('encounter_date', today())
        ->count();

        $completedAppointmentsToday = Appointment::where('clinic_id', $clinicId)
         ->whereDate('appointment_date', today())
         ->where('status', 'completed')
        ->count();

      return view('dashboard', compact(
    'totalPatients',
    'todayAppointments',
    'totalEncounters',
    'recentPatients',
    'todayPatients',
    'todayEncounters',
    'completedAppointmentsToday'
) + ['setupRequired' => false]);
    }
}