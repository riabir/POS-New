<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- =================================================================== --}}
                    {{-- 1. VISIBLE TO ALL LOGGED-IN USERS --}}
                    {{-- =================================================================== --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- === REPORTS SECTION (ALL ROLES) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'user', 'accounts']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Report</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('stocks.index')" class="{{ request()->routeIs('stocks.*') ? 'font-bold' : '' }}">
                                        <span>Current Stocks</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('sales.index')" class="{{ request()->routeIs('sales.*') ? 'font-bold' : '' }}">
                                        <span> Sales Report</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('purchases.index')" class="{{ request()->routeIs('purchases.*') ? 'font-bold' : '' }}">
                                        <span> Purchases Report</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('customer_ledgers.index')" class="{{ request()->routeIs('customer_ledgers.*') ? 'font-bold' : '' }}">
                                        <span> Customer Ledger</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('vendor_ledgers.index')" class="{{ request()->routeIs('vendor_ledgers.*') ? 'font-bold' : '' }}">
                                        <span> Vendor Ledger</span>
                                    </x-dropdown-link>
                                    @if(auth()->user()->role === 'admin')
                                    <x-dropdown-link :href="route('profit.index')" class="{{ request()->routeIs('profit.*') ? 'font-bold' : '' }}">
                                        <span> Profit Report</span>
                                    </x-dropdown-link>
                                    @endif
                                    <x-dropdown-link :href="route('reports.summary')" class="{{ request()->routeIs('reports.*') ? 'font-bold' : '' }}">
                                        <span>Monthly/Yearly Summary</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('commissions.index')" class="{{ request()->routeIs('commissions.*') ? 'font-bold' : '' }}">
                                        <span>Commission Report</span>
                                    </x-dropdown-link>
                                </x-slot>
                        </x-dropdown>
                        @endif
                    </div>


                    {{-- === ADMIN-ONLY SECTION === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (auth()->user()->role === 'admin')
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Management</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('users.index')" class="flex items-center {{ request()->routeIs('users.*') ? 
                                   'bg-gray-200 dark:bg-gray-700' : '' }}">
                                    <span>New User</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('expense_types.index')" class="flex items-center {{ request()->routeIs('expense_types.*') ? 
                                   'bg-gray-200 dark:bg-gray-700' : '' }}">
                                    <span>Expense Types</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('categories.index')" class="{{ request()->routeIs('categories.*') ? 'font-bold' : '' }}">
                                    <span>Categories</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('product_types.index')" class="{{ request()->routeIs('product_types.*') ? 'font-bold' : '' }}">
                                    <span>Product Types</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('brands.index')" class="{{ request()->routeIs('brands.*') ? 'font-bold' : '' }}">
                                    <span>Brands</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('products.index')" class="{{ request()->routeIs('products.*') ? 'font-bold' : '' }}">
                                    <span>Item</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>


                    {{-- === ACCOUNTING SECTION (ADMIN & ACCOUNTS) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'accounts']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Payroll</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('employees.index')" class="{{ request()->routeIs('employees.*') ? 'font-bold' : '' }}">
                                    <span>Employee Report</span>
                                </x-dropdown-link>
                                @if(auth()->user()->role === 'admin')
                                <x-dropdown-link :href="route('employees.create')" class="{{ request()->routeIs('employees.*') ? 'font-bold' : '' }}">
                                    <span>New Employee</span>
                                </x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('salary_structures.index')" class="{{ request()->routeIs('salary_structures.*') ? 'font-bold' : '' }}">
                                    <span>Salary Structure</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('payouts.index')" class="{{ request()->routeIs('payouts.*') ? 'font-bold' : '' }}">
                                    <span>Payouts</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>

                    {{-- === ACCOUNTING SECTION --2 (ADMIN & ACCOUNTS) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'accounts']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Accounts</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('customer_accounts.index')" class="{{ request()->routeIs('customer_accounts.*') ? 'font-bold' : '' }}">
                                    <span>Bill Received</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('customer_advances.create')" class="{{ request()->routeIs('customer_advances.*') ? 'font-bold' : '' }}">
                                    <span>Customer Advance</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('customer_refunds.index')" class="{{ request()->routeIs('customer_refunds.*') ? 'font-bold' : '' }}">
                                    <span>Customer Refund</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('vendor_accounts.index')" class="{{ request()->routeIs('vendor_accounts.*') ? 'font-bold' : '' }}">
                                    <span>Vendor Payment</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('vendor_advances.create')" class="{{ request()->routeIs('vendor_advances.*') ? 'font-bold' : '' }}">
                                    <span>Vendor Advance</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('vendor_refunds.index')" :active="request()->routeIs('vendor_refunds.*')">
                                    {{ __('Vendor Refund') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('shareholders.index')" class="{{ request()->routeIs('shareholders.*') ? 'font-bold' : '' }}">
                                    <span>Partner Management</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>

                    {{-- === MANAGEMENT SECTION -1 (ADMIN & USER) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'user']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Sales</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('sales.create')" class="{{ request()->routeIs('sales.*') ? 'font-bold' : '' }}">
                                    <span>Sales</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('customers.index')" class="{{ request()->routeIs('customers.*') ? 'font-bold' : '' }}">
                                    <span>New Customer</span>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('serial.search')" class="{{ request()->routeIs('serial.*') ? 'font-bold' : '' }}">
                                    <span>All Search</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>

                    {{-- === MANAGEMENT SECTION -2 (ADMIN & USER) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'user']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Purchases</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('purchases.create')" class="{{ request()->routeIs('purchases.*') ? 'font-bold' : '' }}">
                                    <span>Purchase</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('vendors.index')" class="{{ request()->routeIs('vendors.*') ? 'font-bold' : '' }}">
                                    <span>New Vendor</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>


                    {{-- === REPORTS SECTION (ALL ROLES) === --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @if (in_array(auth()->user()->role, ['admin', 'user', 'accounts']))
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Expense</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>
                            <x-slot name="content">
                                <x-slot name="content">
                                    @if (in_array(auth()->user()->role, ['admin', 'accounts']))
                                    <x-dropdown-link :href="route('expenses.index')" class="{{ request()->routeIs('expenses.*') ? 'font-bold' : '' }}">
                                        <span>Expense Approval</span>
                                    </x-dropdown-link>
                                    @endif
                                    <x-dropdown-link :href="route('expenses.paid')" class="{{ request()->routeIs('expenses.*') ? 'font-bold' : '' }}">
                                        <span>Paid Expense History</span>
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('expenses.user.index')" class="{{ request()->routeIs('expenses.user.*') ? 'font-bold' : '' }}">
                                        <span>My Expenses</span>
                                    </x-dropdown-link>
                                </x-slot>
                        </x-dropdown>
                        @endif
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    {{-- Add more responsive links here, with the same @if role checks as above, if needed --}}

                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
</nav>