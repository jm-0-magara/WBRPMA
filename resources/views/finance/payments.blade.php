@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Initial data passed from the controller
        var initialChartLabels = @json($labels);
        var initialChartData = @json($data);
        var initialMonthlyLabels = @json($monthlyLabels);
        var initialMonthlyPaymentsData = @json($monthlyPaymentsData);
        var filterRoute = '{{ route("payments/filter") }}';
        var addPaymentRoute = '{{ route("payments/add") }}';
        var updatePaymentRouteBase = '{{ url("payments/update") }}'; // Base URL for update
        var deletePaymentRouteBase = '{{ url("payments/delete") }}'; // Base URL for delete
        var showPaymentRouteBase = '{{ url("payments/show") }}'; // Base URL for showing a single payment
    </script>
<script src="{{ URL::to('assets/js/pages/payments.js') }}"></script>

<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">Payments</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ Session::has('rentalName') ? Session::get('rentalName') : 'Select a property' }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Payments
                </li>
            </ul>
        </div>

        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu-width group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu-width mt-[97px] px-4">
            <div class="container-fluid">

                <div class="card p-6 mb-6 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                    <h6 class="mb-4 text-15 font-semibold text-slate-800 dark:text-zink-100">Payment Details Overview</h6>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="paymentTypeSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Payment Type</label>
                            <select id="paymentTypeSelect" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                                <option value="all">All Payment Types</option>
                                    <option value="Rent">Rent</option>
                                    <option value="Rent Deposit">Rent Deposit</option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="houseSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Unit No.</label>
                            <select id="houseSelect" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                                <option value="all">All Units</option>
                                @foreach ($houseNos as $houseNo)
                                    <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                            <input type="date" name="startDate" id="startDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                        </div>

                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" name="endDate" id="endDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                        </div>

                        <div class="col-span-full md:col-span-1">
                            <button id="filterButton" type="button" class="btn btn-primary bg-custom-500 text-white border-custom-500 hover:bg-custom-600 w-full">
                                Apply Filter
                            </button>
                        </div>
                        <div class="col-span-full md:col-span-1">
                            <button id="printButton" type="button" class="btn btn-info bg-sky-500 text-white border-sky-500 hover:bg-sky-600 w-full">
                                <i data-lucide="printer" class="inline-block size-4"></i>
                                <span class="align-middle">Print Records</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount Paid</p>
                            <h5 id="totalAmountPaid" class="text-3xl font-bold mt-1 text-slate-800 dark:text-zink-100">Ksh {{ number_format($totalAmountPaid, 2) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                        <div id="paymentsChart" class="apex-charts"></div>
                    </div>
                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                        <div id="monthlyPaymentsChart" class="apex-charts"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-6">
                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md mt-6">
                        <div class="overflow-x-auto">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-15 grow">Payment Records</h6>
                                <div class="shrink-0">
                                    <a href="#!" data-modal-target="addPaymentModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                        <i data-lucide="plus" class="inline-block size-4"></i>
                                        <span class="align-middle">Add Payment</span>
                                    </a>
                                </div>
                            </div>
                            <table class="table-auto w-full text-left whitespace-nowrap text-slate-800 dark:text-zink-100" id="paymentsTable">
                                <thead>
                                    <tr class="bg-slate-100 dark:bg-zink-600">
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Payment ID</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">House No.</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Payment Type</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Amount (Ksh)</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Date Paid</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Payment Method</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Narration</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentsTableBody">
                                    @if(isset($payments) && $payments->count() > 0)
                                    @foreach ($payments as $payment)
                                    <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                                        <td class="px-4 py-2">{{ $payment->paymentID }}</td>
                                        <td class="px-4 py-2">{{ $payment->houseNo }}</td>
                                        <td class="px-4 py-2">{{ $payment->paymentType }}</td>
                                        <td class="px-4 py-2">{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->timePaid)->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2">{{ $payment->paymentMethod }}</td>
                                        <td class="px-4 py-2">{{ $payment->narration ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">
                                            <div class="flex items-center gap-2 justify-end">
                                                <div class="relative group">
                                                    <button type="button" data-modal-target="editPaymentModal-{{ $payment->paymentID }}" class="edit-payment-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">
                                                        <i data-lucide="edit-2" class="size-4"></i>
                                                    </button>
                                                    <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                        Edit
                                                    </div>
                                                </div>
                                                <div class="relative group">
                                                    <button type="button" data-modal-target="deletePaymentModal-{{ $payment->paymentID }}" class="delete-payment-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">
                                                        <i data-lucide="trash-2" class="size-4"></i>
                                                    </button>
                                                    <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                        Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Payment Modal -->
                                    <div id="editPaymentModal-{{ $payment->paymentID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editPaymentModal-{{ $payment->paymentID }}">
                                            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Payment Record</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editPaymentModal-{{ $payment->paymentID }}">
                                                    <i data-lucide="x" class="size-4"></i>
                                                </button>
                                            </div>
                                            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                <form id="updatePaymentForm-{{ $payment->paymentID }}" action="{{ route('payments/update', ['paymentID' => $payment->paymentID]) }}" method="POST">
                                                    @csrf
                                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                        <div>
                                                            <label for="updateHouseNoPayment-{{ $payment->paymentID }}" class="inline-block mb-2 text-base font-medium">Select House</label>
                                                            <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateHouseNoPayment-{{ $payment->paymentID }}" required>
                                                                @foreach ($houseNos as $houseNo)
                                                                <option value="{{ $houseNo }}" @if($payment->houseNo == $houseNo) selected @endif>{{ $houseNo }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="updatePaymentType-{{ $payment->paymentID }}" class="inline-block mb-2 text-base font-medium">Payment Type</label>
                                                            <select name="paymentType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updatePaymentType-{{ $payment->paymentID }}" required>
                                                                @foreach ($paymentTypes as $type)
                                                                <option value="{{ $type }}" @if($payment->paymentType == $type) selected @endif>{{ $type }}</option>
                                                                @endforeach
                                                                <option value="Rent" @if($payment->paymentType == 'Rent') selected @endif>Rent</option>
                                                                <option value="Rent Deposit" @if($payment->paymentType == 'Rent Deposit') selected @endif>Rent Deposit</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="updatePaymentAmount-{{ $payment->paymentID }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount Paid</label>
                                                            <input type="number" step="0.01" name="amount" value="{{ $payment->amount }}" id="updatePaymentAmount-{{ $payment->paymentID }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>
                                                        </div>
                                                        <div>
                                                            <label for="updatePaymentMethod-{{ $payment->paymentID }}" class="inline-block mb-2 text-base font-medium">Payment Method</label>
                                                            <select name="paymentMethod" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updatePaymentMethod-{{ $payment->paymentID }}" required>
                                                                <option value="M-Pesa" @if($payment->paymentMethod == 'M-Pesa') selected @endif>M-Pesa</option>
                                                                <option value="Cash" @if($payment->paymentMethod == 'Cash') selected @endif>Cash</option>
                                                                <option value="Bank Transfer" @if($payment->paymentMethod == 'Bank Transfer') selected @endif>Bank Transfer</option>
                                                                <option value="Cheque" @if($payment->paymentMethod == 'Cheque') selected @endif>Cheque</option>
                                                                <option value="Other" @if($payment->paymentMethod == 'Other') selected @endif>Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-span-full">
                                                            <label for="updatePaymentDate-{{ $payment->paymentID }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Paid</label>
                                                            <input type="date" name="timePaid" id="updatePaymentDate-{{ $payment->paymentID }}" value="{{ \Carbon\Carbon::parse($payment->timePaid)->format('Y-m-d') }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                                        </div>
                                                        <div class="col-span-full">
                                                            <label for="updatePaymentNarration-{{ $payment->paymentID }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Narration (Optional)</label>
                                                            <textarea name="narration" id="updatePaymentNarration-{{ $payment->paymentID }}" class="form-textarea border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" rows="3">{{ $payment->narration ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Payment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Payment Modal -->
                                    <div id="deletePaymentModal-{{ $payment->paymentID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                                            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                                                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                                    <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deletePaymentModal-{{ $payment->paymentID }}">
                                                        <i data-lucide="x" class="size-4"></i>
                                                    </button>
                                                </div>
                                                <div class="p-4 overflow-y-auto">
                                                    <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the payment record for house <span class="font-semibold text-red-500">{{ $payment->houseNo }}</span> with ID <span class="font-semibold text-red-500">{{ $payment->paymentID }}</span>?</p>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deletePaymentModal-{{ $payment->paymentID }}">Cancel</button>
                                                        <form id="deletePaymentForm-{{ $payment->paymentID }}" method="POST" action="{{ route('payments/delete', ['paymentID' => $payment->paymentID]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="paymentID" value="{{ $payment->paymentID }}">
                                                            <button type="submit" class="btn bg-red-500 text-white hover:bg-red-600">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No payment records found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div id="addPaymentModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="addPaymentLabel">Add New Payment</h5>
            <button data-modal-close="addPaymentModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form id="addPaymentForm" method="POST" action="{{ route('payments/add') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                    <div class="xl:col-span-12">
                        <label for="addPaymentID" class="inline-block mb-2 text-base font-medium">Payment ID</label>
                        <input type="text" id="addPaymentID" name="paymentID" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="Enter Payment ID" required>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addHouseNo" class="inline-block mb-2 text-base font-medium">House Number</label>
                        <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addHouseNo" required>
                            <option value="">Select a unit</option>
                            @foreach ($houseNos as $houseNo)
                                <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addPaymentType" class="inline-block mb-2 text-base font-medium">Payment Type</label>
                        <select name="paymentType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addPaymentType" required>
                            <option value="">Select Payment Type</option>
                            <option value="Rent">Rent</option>
                            <option value="Rent Deposit">Rent Deposit</option>
                            @foreach ($paymentTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addAmount" class="inline-block mb-2 text-base font-medium">Amount</label>
                        <input type="number" step="0.01" id="addAmount" name="amount" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="Amount Paid" required>
                    </div>
                    <div class="xl:col-span-6">
                        <label for="addPaymentMethod" class="inline-block mb-2 text-base font-medium">Payment Method</label>
                        <select name="paymentMethod" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addPaymentMethod" required>
                            <option value="">Select Method</option>
                            <option value="M-Pesa">M-Pesa</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="xl:col-span-6">
                        <label for="addTimePaid" class="inline-block mb-2 text-base font-medium">Date Paid</label>
                        <input type="date" id="addTimePaid" name="timePaid" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addNarration" class="inline-block mb-2 text-base font-medium">Narration</label>
                        <textarea id="addNarration" name="narration" class="form-textarea border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" rows="3" placeholder="Enter any additional information"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="reset" data-modal-close="addPaymentModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ URL::to('assets/js/pages/payments.js') }}"></script>
@endsection
