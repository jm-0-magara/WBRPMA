@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var initialChartLabels = @json($labels);
        var initialChartData = @json($data);
        var waterPrice = {{ $waterPrice }};
        var filterRoute = '{{ route("waterdetails/filter") }}';

        var initialUnitsConsumedData = @json($unitsConsumedData);

        var initialPaymentsData = @json($paymentsData);
        var initialBalanceData = @json($balanceData);

    </script>
<script src="{{ URL::to('assets/js/pages/waterdetails.js') }}"></script>

<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden" data-intro="This is the Water Consumption Management page where you can view and manage water consumption records for the selected property." data-step="24">
            <div class="grow">
                <h5 class="text-16">Water Consumption</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ Session::has('rentalName') ? Session::get('rentalName') : 'Select a property' }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Water
                </li>
            </ul>
        </div>

        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu-width group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu-width mt-[97px] px-4">
            <div class="container-fluid">
                
                <div class="card p-6 mb-6 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                    <h6 class="mb-4 text-15 font-semibold text-slate-800 dark:text-zink-100">Water Consumption Details</h6>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                        
                        <div>
                            <button id="filterButton" type="button" class="btn btn-primary bg-custom-500 text-white border-custom-500 hover:bg-custom-600 w-full">
                                Apply Filter
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                            <h5 id="totalAmount" class="text-3xl font-bold mt-1 text-slate-800 dark:text-zink-100">Ksh {{ number_format($totalAmount, 2) }}</h5>
                            <p id="totalUnits" class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ number_format($totalUnits, 2) }} units</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                        <div id="waterChart" class="apex-charts"></div>
                    </div>

<div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md mt-6">
    <div class="overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h6 class="text-15 grow">Water Consumption Records</h6>
            <div class="shrink-0"></div>
            <div class="shrink-0">
                <a href="#!" data-modal-target="addWaterRecordModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                    <i data-lucide="plus" class="inline-block size-4"></i> 
                    <span class="align-middle">Add Record</span>
                </a>
            </div>
        </div>
        <table class="table-auto w-full text-left whitespace-nowrap text-slate-800 dark:text-zink-100" id="waterDetailsTable">
            <thead>
                <tr class="bg-slate-100 dark:bg-zink-600">
                    <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">House No.</th>
                    <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Date</th>
                    <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Units Consumed</th>
                    <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Amount (Ksh)</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">              </th>
                </tr>
            </thead>
            <tbody id="waterDetailsBody">
                @if(isset($waterDetails) && $waterDetails->count() > 0)
                @foreach ($waterDetails as $detail)
                <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                    <td class="px-4 py-2">{{ $detail->houseNo }}</td>
                    <td class="px-4 py-2">{{ $detail->date }}</td>
                    <td class="px-4 py-2">{{ number_format($detail->unitsConsumed, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($detail->unitsConsumed * $waterPrice, 2) }}</td>
                    <td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">
                        <div class="flex items-center gap-2 justify-end">
                            <div class="relative group">
                                <button type="button" data-modal-target="editWaterModal-{{ $detail->waterConsumedID }}" class="edit-reservation-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">
                                    <i data-lucide="edit-2" class="size-4"></i>
                                </button>
                                <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                    Edit
                                </div>
                            </div>
                            <div class="relative group">
                                <button type="button" data-modal-target="deleteWaterModal-{{ $detail->waterConsumedID }}" class="delete-reservation-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                                <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                    Delete
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <div id="editWaterModal-{{ $detail->waterConsumedID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editWaterModal-{{ $detail->waterConsumedID }}">
                        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                            <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Water Record</h5>
                            <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editWaterModal-{{ $detail->waterConsumedID }}">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                        </div>
                        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                            <form id="updateWaterForm-{{ $detail->waterConsumedID }}" action="{{ route('waterdetails/update/{waterConsumedID}', ['waterConsumedID' => $detail->waterConsumedID]) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="updateHouseNo" class="inline-block mb-2 text-base font-medium">Select House</label>
                                        <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateHouseNo-{{ $detail->waterConsumedID }}" required>
                                            <option value="{{ $detail->houseNo }}" selected>{{ $detail->houseNo }}</option>
                                            @foreach ($houseNos as $houseNo)
                                            <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="addDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                        <input type="date" name="date" id="addDate-{{ $detail->waterConsumedID }}" value="{{ $detail->date }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                    </div>
                                    <div>
                                        <label for="addUnitsConsumed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Units Consumed</label>
                                        <input type="number" step="0.01" name="unitsConsumed" value="{{ $detail->unitsConsumed }}" id="addUnitsConsumed-{{ $detail->waterConsumedID }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 50.25" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label for="addNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                        <textarea id="addNotes-{{ $detail->waterConsumedID }}" name="notes" rows="2" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">{{ $detail->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Record</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="deleteWaterModal-{{ $detail->waterConsumedID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                    <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                        <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deleteWaterModal-{{ $detail->waterConsumedID }}">
                                    <i data-lucide="x" class="size-4"></i>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto">
                                <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the water record for house <span id="deleteClientName" class="font-semibold text-red-500">{{ $detail->houseNo }}</span> on <span class="font-semibold text-red-500">{{ $detail->date }}</span>?</p>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deleteWaterModal-{{ $detail->waterConsumedID }}">Cancel</button>
                                    <form id="deleteReservationForm-{{ $detail->waterConsumedID }}" method="POST" action="{{ route('waterdetails/delete/{waterConsumedID}', ['waterConsumedID' => $detail->waterConsumedID]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="waterConsumedID" id="waterConsumedId" value="{{ $detail->waterConsumedID }}">
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
                    <td colspan="5" class="text-center py-4">No water consumption data found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md mt-6">
    <div class="overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h6 class="text-15 grow">Water Payment Records</h6>
            <div class="shrink-0"></div>
             <div class="shrink-0">
                <a href="#!" data-modal-target="addWaterPaymentModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                    <i data-lucide="plus" class="inline-block size-4"></i> 
                    <span class="align-middle">Add Payment</span>
                </a>
            </div>
        </div>
        <table class="table-auto w-full text-left whitespace-nowrap text-slate-800 dark:text-zink-100" id="waterDetailsTable">
            <thead>
                        <tr class="bg-slate-100 dark:bg-zink-600">
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">House No</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Date</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Amount Paid</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Payment Method</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Narration</th>
                            <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">     </th>
                        </tr>
                    </thead>
                    <tbody id="waterPaymentsBody">
                        @if(isset($payments) && $payments->count() > 0)
                        @foreach ($payments as $payment)
                        <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                            <td class="px-4 py-2">{{ $payment->houseNo }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->timePaid)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $payment->paymentMethod }}</td>
                            <td class="px-4 py-2">{{ $payment->narration }}</td>
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

                        <div id="editPaymentModal-{{ $payment->paymentID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                            <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editPaymentModal-{{ $payment->paymentID }}">
                                <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                    <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Payment Record</h5>
                                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editPaymentModal-{{ $payment->paymentID }}">
                                        <i data-lucide="x" class="size-4"></i>
                                    </button>
                                </div>
                                <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                    <form id="updatePaymentForm-{{ $payment->paymentID }}" action="{{route('payments/update',[ 'paymentID' => $payment->paymentID ])}}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                            <div>
                                                <label for="updateHouseNoPayment" class="inline-block mb-2 text-base font-medium">Select House</label>
                                                <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateHouseNoPayment-{{ $payment->paymentID }}" required>
                                                    <option value="{{ $payment->houseNo }}" selected>{{ $payment->houseNo }}</option>
                                                    @foreach ($houseNos as $houseNo)
                                                    <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="updatePaymentDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                                <input type="date" name="timePaid" id="updatePaymentDate-{{ $payment->paymentID }}" value="{{ \Carbon\Carbon::parse($payment->timePaid)->format('Y-m-d') }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                            </div>
                                            <div>
                                                <label for="updatePaymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount Paid</label>
                                                <input type="number" step="0.01" name="amount" value="{{ $payment->amount }}" id="updatePaymentAmount-{{ $payment->paymentID }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>
                                            </div>
                                            <div>
                                                <label for="updatePaymentMethod" class="inline-block mb-2 text-base font-medium">Payment Method</label>
                                                <select name="paymentMethod" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updatePaymentMethod-{{ $payment->paymentID }}" required>
                                                    <option value="M-Pesa" @if($payment->paymentMethod == 'M-Pesa') selected @endif>M-Pesa</option>
                                                    <option value="Cash" @if($payment->paymentMethod == 'Cash') selected @endif>Cash</option>
                                                    <option value="Bank Transfer" @if($payment->paymentMethod == 'Bank Transfer') selected @endif>Bank Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2">
                                                <label for="updatePaymentNarration" class="block text sm font-medium text-gray-700 dark:text-gray-300 mb-1">Narration (Optional)</label>
                                                <textarea id="updatePaymentNarration-{{ $payment->paymentID }}" name="narration" rows="2" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">{{ $payment->narration }}</textarea>
                                            </div>
                                            <input type="hidden" name="paymentType" value="{{ $payment->paymentType }}">
                                        </div>
                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
                                        <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the payment record for house <span class="font-semibold text-red-500">{{ $payment->houseNo }}</span> on <span class="font-semibold text-red-500">{{ \Carbon\Carbon::parse($payment->timePaid)->format('Y-m-d') }}</span>?</p>
                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deletePaymentModal-{{ $payment->paymentID }}">Cancel</button>
                                            <form id="deletePaymentForm-{{ $payment->paymentID }}" action="{{route('payments/delete',[ 'paymentID' => $payment->paymentID ])}}" method="POST">
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
                            <td colspan="5" class="text-center py-4">No water payments found.</td>
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

<div id="addWaterPaymentModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-beSTEen p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="addWaterPaymentLabel">Add Water Payment</h5>
            <button data-modal-close="addEmployeeModal" id="addEmployee"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                    class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form class="create-form" id="create-form" method="POST" action="{{route('payments/add')}}" enctype="multipart/form-data">
                @csrf
                <div id="alert-error-msg"
                    class="hidden px-4 py-3 text-sm text-red-500 border border-transparent rounded-md bg-red-50 dark:bg-red-500/20">
                </div>
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                    <div class="xl:col-span-12">
                        <label for="paymentID" class="inline-block mb-2 text-base font-medium">Payment ID</label>
                        <input type="text" id="paymentID" name="paymentID"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            value="">
                    </div>
                    <div class="xl:col-span-12">
                        <label for="houseNo" class="inline-block mb-2 text-base font-medium">House Number</label>
                        <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addHouseNo" required>
                            <option value="">Select a unit</option>
                            @foreach ($houseNos as $houseNo)
                                <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="amount" class="inline-block mb-2 text-base font-medium">Amount</label>
                        <input type="text" id="amount" name="amount"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            placeholder="Amount Paid">
                    </div>
                    <div class="xl:col-span-12">
                        <label for="paymentType"
                            class="inline-block mb-2 text-base font-medium">Payment Type</label>
                        <input type="text" id="paymentType" name="paymentType"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            value="{{ $paymentType ?? 0}}" readonly>
                    </div>
                    <div class="xl:col-span-6">
                        <label for="paymentMethod" class="inline-block mb-2 text-base font-medium">Payment Method</label>
                        <input type="text" id="paymentMethod" name="paymentMethod"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            placeholder="Enter Payment Method" required="">
                    </div>
                    <div class="xl:col-span-6">
                        <label for="timePaid"
                            class="inline-block mb-2 text-base font-medium">Time Paid</label>
                        <input type="date" id="timePaid" name="timePaid"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            required="">
                    </div>    
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="reset" id="close-modal" data-modal-close="addWaterPaymentModal"
                        class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                    <button type="submit" id="addNew"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 ">Add
                        Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addWaterRecordModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-beSTEen p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="addWaterRecordLabel">Add Water Payment</h5>
            <button data-modal-close="addEmployeeModal" id="addEmployee"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                    class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form id="addWaterForm" action="{{ route('waterdetails/add') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="addHouseNo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select House</label>
                    <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addHouseNo" required>
                        <option value="">Select a unit</option>
                        @foreach ($houseNos as $houseNo)
                            <option value="{{ $houseNo }}">{{ $houseNo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="addDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <input type="date" name="date" id="addDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                </div>
                <div>
                    <label for="addUnitsConsumed" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Units Consumed</label>
                    <input type="number" step="0.01" name="unitsConsumed" id="addUnitsConsumed" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 50.25" required>
                </div>
                <div class="col-span-2">
                    <label for="addNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                    <textarea id="addNotes" name="notes" rows="2" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
            <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600">Add Record</button>
            </div>
        </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script src="{{ URL::to('assets/js/pages/waterdetails.js') }}"></script>
@endsection