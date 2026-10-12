<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $filters['start_date']
            ?? now()->startOfMonth()->toDateString();

        $endDate = $filters['end_date']
            ?? now()->endOfMonth()->toDateString();

        $clinicId = $request->user()->clinic_id;

        if (!$clinicId) {
            return view('reports.index', [
                'setupRequired' => true,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'patientCount' => 0,
                'appointmentCount' => 0,
                'encounterCount' => 0,
                'appointmentStatuses' => collect(),
                'recentPatients' => collect(),
            ]);
        }

        $patientQuery = Patient::where('clinic_id', $clinicId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $appointmentQuery = Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', '>=', $startDate)
            ->whereDate('appointment_date', '<=', $endDate);

        $encounterQuery = Encounter::where('clinic_id', $clinicId)
            ->whereDate('encounter_date', '>=', $startDate)
            ->whereDate('encounter_date', '<=', $endDate);

        $patientCount = (clone $patientQuery)->count();
        $appointmentCount = (clone $appointmentQuery)->count();
        $encounterCount = (clone $encounterQuery)->count();

        $appointmentStatuses = (clone $appointmentQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $recentPatients = (clone $patientQuery)
            ->latest()
            ->take(20)
            ->get();

        $setupRequired = false;

        return view('reports.index', compact(
            'setupRequired',
            'startDate',
            'endDate',
            'patientCount',
            'appointmentCount',
            'encounterCount',
            'appointmentStatuses',
            'recentPatients'
        ));
    }
    public function exportPatients(Request $request)
{
    $filters = $request->validate([
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
    ]);

    $clinicId = $request->user()->clinic_id;

    abort_unless($clinicId, 403, 'Your account is not assigned to a clinic.');

    $startDate = $filters['start_date'];
    $endDate = $filters['end_date'];

    $patients = Patient::where('clinic_id', $clinicId)
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->orderBy('created_at')
        ->get([
            'patient_number',
            'first_name',
            'last_name',
            'created_at',
        ]);

    $filename = 'patient-registrations-' .
        $startDate . '-to-' . $endDate . '.csv';

    return response()->streamDownload(function () use ($patients) {
        $handle = fopen('php://output', 'w');

        // UTF-8 BOM helps Excel display text correctly.
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'Patient Number',
            'First Name',
            'Last Name',
            'Registration Date',
        ]);

        foreach ($patients as $patient) {
            fputcsv($handle, [
                $patient->patient_number,
                $patient->first_name,
                $patient->last_name,
                $patient->created_at->format('Y-m-d'),
            ]);
        }

        fclose($handle);
    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
public function exportAppointments(Request $request)
{
    $filters = $request->validate([
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
    ]);

    $clinicId = $request->user()->clinic_id;

    abort_unless(
        $clinicId,
        403,
        'Your account is not assigned to a clinic.'
    );

    $appointments = Appointment::with('patient')
        ->where('clinic_id', $clinicId)
        ->whereDate('appointment_date', '>=', $filters['start_date'])
        ->whereDate('appointment_date', '<=', $filters['end_date'])
        ->orderBy('appointment_date')
        ->orderBy('start_time')
        ->get();

    $filename = 'appointments-' .
        $filters['start_date'] .
        '-to-' .
        $filters['end_date'] .
        '.csv';

    return response()->streamDownload(function () use ($appointments) {

        $handle = fopen('php://output', 'w');

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'Appointment Number',
            'Appointment Date',
            'Start Time',
            'End Time',
            'Patient Number',
            'Patient Name',
            'Type',
            'Status',
            'Reason',
        ]);

        foreach ($appointments as $appointment) {

            $patientName = $appointment->patient
                ? $appointment->patient->last_name . ', ' .
                  $appointment->patient->first_name
                : '';

            fputcsv($handle, [
                $appointment->appointment_number,
                $appointment->appointment_date->format('Y-m-d'),
                $appointment->start_time,
                $appointment->end_time,
                $appointment->patient?->patient_number,
                $patientName,
                $appointment->appointment_type,
                $appointment->status,
                $appointment->reason,
            ]);
        }

        fclose($handle);

    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
public function exportEncounters(Request $request)
{
    $filters = $request->validate([
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
    ]);

    $clinicId = $request->user()->clinic_id;

    abort_unless(
        $clinicId,
        403,
        'Your account is not assigned to a clinic.'
    );

    $encounters = Encounter::with('patient')
        ->where('clinic_id', $clinicId)
        ->whereDate('encounter_date', '>=', $filters['start_date'])
        ->whereDate('encounter_date', '<=', $filters['end_date'])
        ->orderBy('encounter_date')
        ->get();

    $filename = 'consultations-' .
        $filters['start_date'] .
        '-to-' .
        $filters['end_date'] .
        '.csv';

    return response()->streamDownload(function () use ($encounters) {

        $handle = fopen('php://output', 'w');

        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'Encounter Number',
            'Encounter Date',
            'Patient Number',
            'Patient Name',
            'Encounter Type',
            'Status',
        ]);

        foreach ($encounters as $encounter) {

            $patientName = $encounter->patient
                ? $encounter->patient->last_name . ', ' .
                  $encounter->patient->first_name
                : '';

            fputcsv($handle, [
                $encounter->encounter_number,
                $encounter->encounter_date->format('Y-m-d H:i'),
                $encounter->patient?->patient_number,
                $patientName,
                $encounter->encounter_type,
                $encounter->status,
            ]);
        }

        fclose($handle);

    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
}