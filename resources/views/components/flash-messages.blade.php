@if (session()->has('message'))
    <div
         class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-md {{ session('message_type') === 'success' ? 'bg-green-50 text-green-800 dark:bg-green-900 dark:text-green-100' : 'bg-red-50 text-red-800 dark:bg-red-900 dark:text-red-100' }}">
        <div class="flex items-center">
            @if(session('message_type') === 'success')
                <svg class="h-6 w-6 text-green-400 dark:text-green-300 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                <svg class="h-6 w-6 text-red-400 dark:text-red-300 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            @endif
            <p class="text-sm font-medium">{{ session('message') }}</p>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-md bg-red-50 text-red-800 dark:bg-red-900 dark:text-red-100">
        <div class="flex items-center">
            <svg class="h-6 w-6 text-red-400 dark:text-red-300 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif
