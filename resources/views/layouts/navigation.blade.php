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

                    <!-- Sales Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
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

                            <!-- Sales Section -->
                            <x-slot name="content">
                                <x-dropdown-link :href="route('sales.create')">
                                    {{ __('Sales') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('sales.index')">
                                    {{ __('Sales Report') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('customers.index')">
                                    {{ __('New Customers') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('customer_ledgers.index')">
                                    {{ __('Customer Ledger') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profit.index')">
                                    {{ __('Profit Report') }}
                                </x-dropdown-link>
                                <!-- Summary Report Link -->
                                <x-dropdown-link :href="route('reports.summary')">
                                    {{ __('Monthly/Yearly Summary') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('commissions.index')">
                                    {{ __('Commission Report') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('serial.search')">
                                    {{ __('All Search') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Vendor Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Vendor</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>

                            <!-- Sales Section -->
                            <x-slot name="content">
                                <x-dropdown-link :href="route('vendors.index')">
                                    {{ __('All Vendor') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('vendor_ledgers.index')">
                                    {{ __('Vendor Ledgure') }}
                                </x-dropdown-link>

                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Vendor Section -->
                    <!-- <x-nav-link :href="route('vendors.index')" :active="request()->routeIs('vendors.index')">
                        Vendor
                    </x-nav-link> -->



                    <!-- Store Section -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <span>Store</span>
                                    <svg class="ms-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('stocks.index')">
                                    {{ __('Current Stocks') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('purchases.create')">
                                    {{ __('Purchase') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('purchases.index')">
                                    {{ __('Purchase History') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- System Setup Section -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <div>System Setup</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('categories.index')">
                                    {{ __('Catagory Setup') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('product_types.index')">
                                    {{ __('Product Type Setup') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('brands.index')">
                                    {{ __('Brand Setup') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('products.index')">
                                    {{ __('Item Setup') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('dashboard')">
                                    {{ __('LSP Setup') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Payroll Section -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
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
                                <x-dropdown-link :href="route('employees.index')">
                                    {{ __('Employee Report') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('employees.create')">
                                    {{ __('New Employee') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('salary_structures.index')">
                                    {{ __('Salary Structure') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('payouts.index')">
                                    {{ __('Bonus') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('users.create')">
                                    {{ __('User Registration') }}
                                </x-dropdown-link>
                             
                                <x-dropdown-link :href="route('expenses.index')">
                                    {{ __('Expense Approval') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('expenses.paid')">
                                    {{ __('Paid Expense History') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('expenses.user.index')">
                                    {{ __('My Expenses') }}
                                </x-dropdown-link>
                            

                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Accounts Section -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <div
                                    class="flex items-center cursor-pointer text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                    <div>Accounts</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('customer_accounts.index')">
                                    {{ __('Bill Received') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('customer_advances.create')">
                                    {{ __('Customer Advance') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('vendor_accounts.index')">
                                    {{ __('Vendor Payment') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('vendor_advances.create')">
                                    {{ __('Vendor Advance') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('expense_types.index')">
                                    {{ __('Expense Type') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('expenses.index')">
                                    {{ __('Expense') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('shareholders.index')">
                                    {{ __('Partner Management') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
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