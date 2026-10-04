<div class="space-y-4">
    <div class="text-sm text-gray-500 dark:text-gray-400">
        Total of <strong class="text-primary-600 dark:text-primary-400">{{ $users->count() }}</strong> participants achieved a perfect score of 30/30 in this week's quiz.
    </div>

    @if($users->isEmpty())
        <div class="p-4 text-center text-sm text-gray-500 bg-gray-50 dark:bg-gray-800 rounded-lg">
            No participants scored 30/30 for this week.
        </div>
    @else
        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">#</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">WhatsApp</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Locality</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Age</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                    @foreach($users as $index => $user)
                        <tr>
                            <td class="px-4 py-2 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-4 py-2 font-mono text-xs text-primary-600 dark:text-primary-400">{{ $user->whatsapp_number }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $user->locality ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $user->age ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
