@php
    use App\Models\User;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isSuperAdmin())
                <!-- Super Admin Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Super Admin Dashboard</h3>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Pending Admins Table -->
                        <div class="mt-8">
                            <h4 class="text-lg font-medium mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 500; margin-bottom: 1rem;">Pending Admin Approvals</h4>
                            @php
                                $pendingAdmins = User::where('role', 'admin')
                                    ->whereDoesntHave('tenant')
                                    ->get();
                            @endphp
                            <x-data-table :headers="['Name', 'Email', 'Status', 'Actions']">
                                @if($pendingAdmins->count() > 0)
                                    @foreach($pendingAdmins as $admin)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $admin->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $admin->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('admin.approve', $admin->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">Approve</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No pending admin approvals
                                        </td>
                                    </tr>
                                @endif
                            </x-data-table>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->isAdmin())
                <!-- Admin Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Admin Dashboard</h3>
                        @if(auth()->user()->isAdmin() && !auth()->user()->tenant)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6" style="background-color: #fefce8; border-left-width: 4px; border-left-color: #facc15; padding: 1rem; margin-bottom: 1.5rem;">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" style="height: 1.25rem; width: 1.25rem; color: #facc15;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700" style="color: #92400e; font-size: 0.875rem;">
                                            Your admin account is pending approval from the super admin. You will receive an email once your account has been approved.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(auth()->user()->tenant && auth()->user()->tenant->is_active)
                            <!-- Admin Information -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-purple-50 p-4 rounded-lg" style="background-color: #faf5ff; padding: 1rem; border-radius: 0.5rem;">
                                    <h4 class="font-medium text-purple-700" style="color: #7e22ce; font-weight: 500;">Total Students</h4>
                                    <p class="text-3xl font-bold text-purple-800 mt-2" style="color: #6b21a8; font-size: 1.875rem; font-weight: 700; margin-top: 0.5rem;">
                                        {{ \App\Models\User::where('role', 'student')->count() }}
                                    </p>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg" style="background-color: #fefce8; padding: 1rem; border-radius: 0.5rem;">
                                    <h4 class="font-medium text-yellow-700" style="color: #a16207; font-weight: 500;">Database Name</h4>
                                    <p class="text-sm font-medium text-yellow-800 mt-2" style="color: #854d0e; font-size: 0.875rem; font-weight: 500; margin-top: 0.5rem;">
                                        {{ auth()->user()->tenant->db_name }}
                                    </p>
                                </div>
                                <div class="bg-pink-50 p-4 rounded-lg" style="background-color: #fdf2f8; padding: 1rem; border-radius: 0.5rem;">
                                    <h4 class="font-medium text-pink-700" style="color: #be185d; font-weight: 500;">Status</h4>
                                    <p class="text-sm font-medium text-pink-800 mt-2" style="color: #9d174d; font-size: 0.875rem; font-weight: 500; margin-top: 0.5rem;">
                                        Active
                                    </p>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="mt-8">
                                <h4 class="text-lg font-medium mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 500; margin-bottom: 1rem;">Registered Students</h4>
                                @php
                                    $students = \App\Models\User::where('role', 'student')->get();
                                @endphp
                                <x-data-table :headers="['Name', 'Email', 'Status', 'Actions']">
                                    @foreach($students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.students.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-data-table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-500" style="color: #6b7280;">
                                    Your account is pending approval from the super admin.
                                    You will receive an email with your database credentials once approved.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Student Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Student Dashboard</h3>
                        <p class="text-gray-600" style="color: #4b5563;">
                            Welcome to your dashboard. Here, you can access your student records and track your progress.
                        </p>

                        <!-- Student Records Table -->
                        <div class="mt-8">
                            <h4 class="text-lg font-medium mb-4" style="color: #1f2937; font-size: 1.125rem; font-weight: 500; margin-bottom: 1rem;">Your Student Records</h4>
                            @php
                                $studentRecords = \App\Models\User::where('role', 'student')
                                    ->where('id', auth()->user()->id)
                                    ->first()->records;
                            @endphp
                            <x-data-table :headers="['Date', 'Type', 'Status', 'Actions']">
                                @foreach($studentRecords as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $record->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $record->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $record->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('student.records.show', $record->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-data-table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
