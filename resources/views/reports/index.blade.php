<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reports and Analytics
        </h2>
     

        
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($setupRequired)
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                    Your account is not assigned to a clinic.
                    Contact your administrator.
                </div>
            @else
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Select Reporting Period
                    </h3>

                    <form method="GET"
                          action="{{ route('reports.index') }}"
                          class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <label for="start_date"
                                   class="block text-sm font-medium">
                                Start Date
                            </label>

                            <input type="date"
                                   id="start_date"
                                   name="start_date"
                                   value="{{ $startDate }}"
                                   required
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div>
                            <label for="end_date"
                                   class="block text-sm font-medium">
                                End Date
                            </label>

                            <input type="date"
                                   id="end_date"
                                   name="end_date"
                                   value="{{ $endDate }}"
                                   required
                                   class="mt-1 w-full rounded-md border-gray-300">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="bg-blue-600 text-purple px-5 py-2 rounded-md">
                                Generate Report
                            </button>
                             <a href="{{ route('reports.patients.export', [
                         'start_date' => $startDate,
                            'end_date' => $endDate,
                        ]) }}"
                        class="inline-block bg-green-600 text-purple px-4 py-2 rounded-md mb-4">
                        Export Patient Report (CSV)
                        </a>
                        </div>
                        <div class="flex flex-wrap gap-3 mb-6">

    <a href="{{ route('reports.patients.export', [
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]) }}"
       class="bg-green-600 text-purple px-4 py-2 rounded-md">
        Export Patients
    </a>

    <a href="{{ route('reports.appointments.export', [
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]) }}"
       class="bg-blue-600 text-purple px-4 py-2 rounded-md">
        Export Appointments
    </a>

    <a href="{{ route('reports.encounters.export', [
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]) }}"
       class="bg-purple-600 text-purple px-4 py-2 rounded-md">
        Export Consultations
    </a>

</div>
                    </form>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Reporting period:
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                    –
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            New Patient Registrations
                        </p>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($patientCount) }}
                        </p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            Appointments
                        </p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ number_format($appointmentCount) }}
                        </p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            Consultations
                        </p>
                        <p class="text-3xl font-bold text-purple-600">
                            {{ number_format($encounterCount) }}
                        </p>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">
                        Appointment Status Summary
                    </h3>

                    @if ($appointmentStatuses->isEmpty())
                        <p class="text-gray-500">
                            No appointments were found for this period.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left">
                                            Status
                                        </th>
                                        <th class="px-4 py-3 text-right">
                                            Number of Appointments
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($appointmentStatuses as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                {{ number_format($item->total) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Patient Registrations
                    </h3>

                    @if ($recentPatients->isEmpty())
                        <p class="text-gray-500">
                            No new patient registrations were found.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left">
                                            Patient Number
                                        </th>
                                        <th class="px-4 py-3 text-left">
                                            Patient Name
                                        </th>
                                        <th class="px-4 py-3 text-left">
                                            Registration Date
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($recentPatients as $patient)
                                        <tr>
                                            <td class="px-4 py-3">
                                                {{ $patient->patient_number }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $patient->last_name }},
                                                {{ $patient->first_name }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $patient->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>