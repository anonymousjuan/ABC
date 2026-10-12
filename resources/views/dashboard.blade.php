<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Health Management Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($setupRequired)
                <div class="bg-yellow-100 border border-yellow-300
                            text-yellow-800 p-4 rounded-lg">
                    <strong>Clinic setup required.</strong>
                    Your account is not assigned to a clinic.
                    Please have an authorized administrator complete
                    the clinic and user assignment.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            Total Patients
                        </p>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($totalPatients) }}
                        </p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            Today's Appointments
                        </p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ number_format($todayAppointments) }}
                        </p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <p class="text-sm text-gray-500">
                            Total Consultations
                        </p>
                        <p class="text-3xl font-bold text-purple-600">
                            {{ number_format($totalEncounters) }}
                        </p>
                    </div>

                </div>

                <div class="bg-white shadow rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold mb-4">
                        Recently Registered Patients
                    </h3>

                    @if ($recentPatients->isEmpty())
                        <p class="text-gray-500">
                            No patient records found.
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
                                            Date Registered
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