<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Payout Details') }}
            </h2>
            <a href="{{ route('payouts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Payouts List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium">For: <span class="text-indigo-600 dark:text-indigo-400">{{ $payout->employee->fullName }}</span></h3>
                            <p class="text-sm text-gray-500">{{ $payout->employee->designation }} | ID: {{ $payout->employee->emp_id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">Payout Date</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $payout->payout_date->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Payout Type</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">{{ $payout->payout_type }}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 bg-gray-50 dark:bg-gray-700/50 font-bold">
                                <dt class="text-sm font-medium">Amount</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 text-green-600 dark:text-green-400">${{ number_format($payout->amount, 2) }}</dd>
                            </div>
                            @if($payout->notes)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $payout->notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('payouts.edit', $payout->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('payouts.destroy', $payout->id) }}" onsubmit="return confirm('Are you sure you want to delete this payout?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>