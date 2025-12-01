<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('/images/logo_sijar.png') }}">
    <title>Activity Log Details</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-['Poppins']">
    @include('layouts.navigationadmin')
    <main class="pt-24">
        <div class="container mx-auto px-4 py-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.activitylogger.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Back to Activity Logger
                </a>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                    <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Activity Log Details
                    </h1>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">Basic
                            Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">Log ID</label>
                                <div class="text-lg font-bold text-gray-900">#{{ $activityLogger->id }}</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">Action</label>
                                <div>
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold text-white bg-{{ $activityLogger->getActionBadgeColor() }}-500">
                                        {{ ucfirst($activityLogger->action) }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">Date & Time</label>
                                <div class="font-semibold text-gray-900">
                                    {{ $activityLogger->created_at->format('M j, Y H:i:s') }}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">IP Address</label>
                                <div>
                                    <code
                                        class="text-xs bg-gray-200 px-2 py-1 rounded">{{ $activityLogger->ip_address }}</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">User
                            Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">User</label>
                                <div class="font-semibold text-gray-900">{{ $activityLogger->user->name ?? 'System' }}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">Role</label>
                                <div class="text-gray-700">{{ $activityLogger->role ?? '-' }}</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-semibold text-gray-600 mb-2 block">User Agent</label>
                                <div>
                                    <small
                                        class="text-gray-600">{{ Str::limit($activityLogger->user_agents, 80) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Model Information -->
                    @if($activityLogger->model)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">Model
                                Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-semibold text-gray-600 mb-2 block">Model</label>
                                    <div>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                                            {{ $activityLogger->model }}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-semibold text-gray-600 mb-2 block">Model ID</label>
                                    <div>
                                        @if($activityLogger->model_id)
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-400 text-white">#{{ $activityLogger->model_id }}</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Changes Table -->
                    @if($activityLogger->old_values || $activityLogger->new_values)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">Changes Made
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-1/3">Field
                                            </th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-1/3">Old
                                                Value</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-1/3">New
                                                Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $oldValues = $activityLogger->old_values ?? [];
                                            $newValues = $activityLogger->new_values ?? [];
                                            $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                        @endphp

                                        @foreach($allKeys as $key)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="px-4 py-3 font-semibold text-gray-900">
                                                    {{ Str::title(str_replace('_', ' ', $key)) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if(array_key_exists($key, $oldValues))
                                                        <span class="text-red-600">
                                                            {{ is_array($oldValues[$key]) ? json_encode($oldValues[$key]) : $oldValues[$key] }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">Empty</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if(array_key_exists($key, $newValues))
                                                        <span class="text-green-600">
                                                            {{ is_array($newValues[$key]) ? json_encode($newValues[$key]) : $newValues[$key] }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">Empty</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- URL -->
                    @if($activityLogger->url)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-blue-500">URL Accessed
                            </h2>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <code class="text-xs text-gray-700 break-all">{{ $activityLogger->url }}</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>

</html>