<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard-Purchase-History') }}
        </h2>
    </x-slot>
    </form>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">


                        <br><br><br>

                        <h1>Purchase List</h1>
                        <br>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table" class="pagination">
                            <thead>
                                <tr>
                                    <th>P_No</th>
                                    <th>P_Date </th>
                                    <th>Vendor</th>
                                    <th>Remarks</th>
                                    <th>Item </th>
                                    <th>P_SN </th>
                                    <th>Unit Price </th>
                                    <th>Qty </th>
                                    <th>Total Price </th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->purchase_no}} </td>
                                        <td>{{ $purchase->purchase_date}} </td>
                                        <td>{{ $purchase->vendor->vendor_name}} </td>
                                        <td>{{ $purchase->remarks}} </td>
                                        <td>{{ $purchase->product?->model }}</td>
                                        <td>{{ $purchase->p_sn}} </td>
                                        <td>{{ $purchase->unit_price}} </td>
                                        <td>{{ $purchase->quantity}} </td>
                                        <td>{{ $purchase->total_price}} </td>

                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>