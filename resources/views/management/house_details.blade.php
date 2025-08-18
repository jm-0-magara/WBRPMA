@extends('layouts.master')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var waterConsumptionData = @json($waterConsumptionData);
    var waterConsumptionLabels = @json($waterConsumptionLabels);
    var paymentRecordsChartData = @json($paymentRecordsChartData);
    var houseNo = @json($house->houseNo);
    var paymentTypes = @json($paymentTypes);
    var filterPaymentsRoute = '{{ route("management/house_details/filter", ["houseNo" => ":houseNo"]) }}';
    var deletePaymentRoute = '{{ route("payments/delete", ["paymentID" => ":paymentID"]) }}';
    var updatePaymentRoute = '{{ route("payments/update", ["paymentID" => ":paymentID"]) }}';
</script>
<script src="{{ asset('assets/js/pages/house_details_charts.js') }}"></script>
@if($house->status == 'Occupied' || $house->status == 'Reserved')
<script src="{{ asset('assets/js/pages/house_details_filters.js') }}"></script>
@endif

<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">House Details: {{ $house->houseNo }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="{{ route('management/houses/page') }}" class="text-slate-400 dark:text-zink-200">Houses</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    House {{ $house->houseNo }}
                </li>
            </ul>
        </div>
        
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12 xl:col-span-5">
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <div class="flex items-center gap-4">
                        @if($house->status == 'Occupied')
                        <img src="{{ $tenant->img ?? asset('assets/images/userDefault.png') }}" alt="Tenant Image" class="size-24 rounded-full object-cover shadow-md">
                        <div>
                            <h4 class="text-2xl font-bold">{{ $tenant->names ?? 'Vacant' }}</h4>
                            <p class="text-slate-500 dark:text-zink-200 mt-1">Unit: {{ $house->houseNo }} | Status: <span class="font-semibold {{ $house->status == 'Occupied' ? 'text-green-500' : ($house->status == 'Reserved' ? 'text-yellow-500' : 'text-red-500') }}">{{ $house->status }}</span></p>
                                <p class="text-sm text-slate-500 dark:text-zink-200">Joined: {{ \Carbon\Carbon::parse($tenant->dateAdded)->format('F j, Y') }}</p>
                        </div>
                         @elseif($house->status == 'Reserved')
                            <div class="size-24 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-600/20 dark:text-yellow-500 shadow-md">
                                <i data-lucide="calendar-check" class="size-12"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold">Reserved by {{ $reservation->clientName ?? 'N/A' }}</h4>
                                <p class="text-slate-500 dark:text-zink-200 mt-1">Unit: {{ $house->houseNo }} | Status: <span class="font-semibold text-yellow-500">Reserved</span></p>
                                @if($reservation)
                                    <p class="text-sm text-slate-500 dark:text-zink-200">Entry Date: {{ \Carbon\Carbon::parse($reservation->reservationDate)->format('F j, Y') }}</p>
                                @endif
                            </div>
                        @else
                            <div class="size-24 flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-600/20 dark:text-red-500 shadow-md">
                                <i data-lucide="home" class="size-12"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold">House is Vacant</h4>
                                <p class="text-slate-500 dark:text-zink-200 mt-1">Unit: {{ $house->houseNo }} | Status: <span class="font-semibold text-red-500">{{ $house->status }}</span></p>
                                <p class="text-sm text-slate-500 dark:text-zink-200">This house is available for new tenants.</p>
                            </div>
                        @endif
                    </div>
                    @if($house->status == 'Occupied')
                    <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                        <h6 class="text-lg font-semibold mb-3">Contact Information</h6>
                        <ul class="space-y-2 text-slate-600 dark:text-zink-300">
                            <li><i data-lucide="phone" class="inline-block size-4 mr-2 text-slate-500"></i> Phone: 0{{ $tenant->phoneNo }}</li>
                            <li><i data-lucide="mail" class="inline-block size-4 mr-2 text-slate-500"></i> Email: {{ $tenant->email }}</li>
                            <li><i data-lucide="credit-card" class="inline-block size-4 mr-2 text-slate-500"></i> ID Number: {{ $tenant->IDNO }}</li>
                        </ul>
                    </div> 
                    <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="" class="flex items-center justify-center size-[37.5px] p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"><i data-lucide="mail" class="size-4"></i></a>          
                            <div class="flex gap-2 2xl:justify-end">
                                <button data-modal-target="addPaymentModal" type="button" class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Payment</button>
                            </div>
                        </div>
                    </div>
                    @elseif($house->status == 'Reserved' && $reservation)
                        <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                            <h6 class="text-lg font-semibold mb-3">Reservation Details</h6>
                            <ul class="space-y-2 text-slate-600 dark:text-zink-300">
                                <li><i data-lucide="user" class="inline-block size-4 mr-2 text-slate-500"></i> Client Name: {{ $reservation->clientName }}</li>
                                <li><i data-lucide="phone" class="inline-block size-4 mr-2 text-slate-500"></i> Phone: 0{{ $reservation->clientPhoneNo }}</li>
                                <li><i data-lucide="mail" class="inline-block size-4 mr-2 text-slate-500"></i> Email: {{ $reservation->clientEmail }}</li>
                                <li><i data-lucide="credit-card" class="inline-block size-4 mr-2 text-slate-500"></i> ID Number: {{ $reservation->clientIDNo }}</li>
                            </ul>
                        </div>   
                    <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="" class="flex items-center justify-center size-[37.5px] p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"><i data-lucide="mail" class="size-4"></i></a>          
                            <div class="flex gap-2 2xl:justify-end">
                                <button data-modal-target="addPaymentModal" type="button" class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Reservation Payment</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-span-12 xl:col-span-7">
                @if($house->status == 'Occupied')
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6 mb-5">
                    @if($house->isPaid)
                        <div class="flex items-center gap-3">
                            <span class="size-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-600/20 dark:text-green-500">
                                <i data-lucide="check" class="size-5"></i>
                            </span>
                            <div>
                                <h6 class="text-lg font-bold text-green-600 dark:text-green-500">Rent is fully paid for this month.</h6>
                                <p class="text-sm text-slate-500 dark:text-zink-200">Amount paid this month: <strong>Ksh. {{ number_format($paidThisMonth, 2) }}</strong></p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="size-10 flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-600/20 dark:text-red-500">
                                <i data-lucide="alert-triangle" class="size-5"></i>
                            </span>
                            <div>
                                <h6 class="text-lg font-bold text-red-600 dark:text-red-500">Rent is due for this month.</h6>
                                <p class="text-sm text-slate-500 dark:text-zink-200">
                                    Amount paid this month: <strong>Ksh. {{ number_format($paidThisMonth, 2) }}</strong>.
                                    Amount due: <strong>Ksh. {{ number_format($houseRent - $paidThisMonth, 2) }}</strong>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                @elseif($house->status == 'Recently Evacuated')
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <h6 class="text-lg font-semibold mb-3">Previous Tenant Details</h6>
                    @if($deletedTenant)
                        <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                            <ul class="space-y-2 text-slate-600 dark:text-zink-300">
                                <li><i data-lucide="user" class="inline-block size-4 mr-2 text-slate-500"></i> Name: {{ $deletedTenant->names }}</li>
                                <li><i data-lucide="phone" class="inline-block size-4 mr-2 text-slate-500"></i> Phone: 0{{ $deletedTenant->phoneNo }}</li>
                                <li><i data-lucide="calendar" class="inline-block size-4 mr-2 text-slate-500"></i> Date Evacuated: {{ \Carbon\Carbon::parse($deletedTenant->dateDeleted)->format('F j, Y') }}</li>
                            </ul>
                        </div> 
                        <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                            <h6 class="text-lg font-semibold mb-3">Outstanding Debt</h6>
                            <p class="text-3xl font-bold text-red-500">Ksh. {{ number_format($deletedTenant->debt, 2) }}</p>
                            <p class="text-slate-500 dark:text-zink-200 mt-1">This is the amount owed by the previous tenant.</p>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="size-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 dark:bg-zink-600 dark:text-zink-200">
                                <i data-lucide="info" class="size-5"></i>
                            </span>
                            <div>
                                <h6 class="text-lg font-bold text-slate-600 dark:text-zink-200">No Previous Tenant Found</h6>
                                <p class="text-sm text-slate-500 dark:text-zink-200">This house has no recent evacuation record.</p>
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                @if($house->status == 'Occupied')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                        <h6 class="text-lg font-semibold mb-3">Rental Payment Summary</h6>
                        <p class="text-3xl font-bold text-slate-800 dark:text-zink-100">Ksh. {{ number_format($totalRentPaid, 2) }}</p>
                        <p class="text-slate-500 dark:text-zink-200 mt-1">Total Rent Paid</p>
                        <hr class="my-4 border-slate-200 dark:border-zink-500">
                        <p class="text-3xl font-bold {{ $rentDebt > 0 ? 'text-red-500' : 'text-green-500' }}">Ksh. {{ number_format($rentDebt, 2) }}</p>
                        <p class="text-slate-500 dark:text-zink-200 mt-1">Rent Debt</p>
                        <p class="text-sm mt-3 text-slate-500 dark:text-zink-200">Monthly Rent: Ksh. {{ number_format($houseRent, 2) }}</p>
                    </div>

                    <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                        <h6 class="text-lg font-semibold mb-3">Water Payment Summary</h6>
                        <p class="text-3xl font-bold text-slate-800 dark:text-zink-100">Ksh. {{ number_format($totalWaterPaid, 2) }}</p>
                        <p class="text-slate-500 dark:text-zink-200 mt-1">Total Water Paid</p>
                        <hr class="my-4 border-slate-200 dark:border-zink-500">
                        <p class="text-3xl font-bold {{ $waterDebt > 0 ? 'text-red-500' : 'text-green-500' }}">Ksh. {{ number_format($waterDebt, 2) }}</p>
                        <p class="text-slate-500 dark:text-zink-200 mt-1">Water Debt</p>
                        <p class="text-sm mt-3 text-slate-500 dark:text-zink-200">Price per Unit: Ksh. {{ number_format($waterPricePerUnit, 2) }}</p>
                    </div>
                </div>
                @elseif($house->status == 'Reserved')
                    <div class="col-span-12 xl:col-span-5">
                        <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                            <h6 class="text-lg font-semibold mb-3">Reservation Summary</h6>
                            <p class="text-3xl font-bold text-slate-800 dark:text-zink-100">Ksh. {{ number_format($reservationAmount, 2) }}</p>
                            <p class="text-slate-500 dark:text-zink-200 mt-1">Amount Deposited for Reservation</p>
                            <div class="mt-6 border-t pt-4 border-slate-200 dark:border-zink-500">
                                <p class="text-3xl font-bold {{ $rentDebt > 0 ? 'text-red-500' : 'text-green-500' }}">Ksh. {{ number_format($rentDebt, 2) }}</p>
                                <p class="text-slate-500 dark:text-zink-200 mt-1">Balance</p>
                                <p class="text-sm mt-3 text-slate-500 dark:text-zink-200">Monthly Rent {{$house->houseNo}}: Ksh. {{ number_format($houseRent, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if($house->status == 'Occupied')
            <div class="col-span-12 xl:col-span-6">
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <h6 class="text-lg font-semibold mb-4">Payment Records</h6>
                    <div id="payment-records-chart" class="apex-charts"></div>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <h6 class="text-lg font-semibold mb-4">Water Consumption</h6>
                    <div id="water-consumption-chart" class="apex-charts"></div>
                </div>
            </div>
            @endif
            @if($house->status == 'Occupied')
            <div class="col-span-12 lg:col-span-4">
                 <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <h6 class="text-lg font-semibold mb-3">Unpaid Rent Months</h6>
                    @if(count($unpaidMonths) > 0)
                        <div class="space-y-2">
                            @foreach($unpaidMonths as $month)
                            <div class="flex items-center justify-between py-2 border-b border-dashed border-slate-200 dark:border-zink-500">
                                <span class="text-slate-500 dark:text-zink-200">{{ $month }}</span>
                                <span class="text-red-500 font-semibold">Unpaid</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-500 dark:text-zink-200">All rent payments are up to date. 👍</p>
                    @endif
                    <hr class="my-4 border-slate-200 dark:border-zink-500">
                    @if(count($monthlyBalances) > 0)
                        <h6 class="text-lg font-semibold mb-3">Monthly Balances</h6>
                        <ul class="space-y-2">
                            @foreach($monthlyBalances as $month => $balance)
                            <li class="flex items-center justify-between py-2 border-b border-dashed border-slate-200 dark:border-zink-500">
                                <span class="text-slate-500 dark:text-zink-200">{{ $month }}</span>
                                <span class="{{ $balance > 0 ? 'text-red-500' : 'text-green-500' }} font-semibold">Ksh. {{ number_format($balance, 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-slate-500 dark:text-zink-200">No monthly balances available.</p>
                    @endif
                </div>
            </div>
            <div class="col-span-12 lg:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6 mb-5">
                        @if($tenant->rentDepositPaid)
                            <button data-modal-target="DepositModal">
                                <div class="flex items-center gap-3">
                                    <span class="size-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-600/20 dark:text-green-500">
                                        <i data-lucide="check" class="size-5"></i>
                                    </span>
                                    <div>
                                        <h6 class="text-lg font-bold text-green-600 dark:text-green-500">Rent Deposit was fully paid</h6>
                                    </div>
                                </div>
                            </button>
                        @else
                            <button data-modal-target="DepositModal">
                                <div class="flex items-center gap-3">
                                    <span class="size-10 flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-600/20 dark:text-red-500">
                                        <i data-lucide="alert-triangle" class="size-5"></i>
                                    </span>
                                    <div>
                                        <h6 class="text-lg font-bold text-red-600 dark:text-red-500">Rent Deposit not cleared</h6>
                                        <p class="text-sm text-slate-500 dark:text-zink-200">
                                            Rent Deposit due: <strong>Ksh. {{ number_format($tenantDepositBalance, 2) }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @endif
                    </div>
                    <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6 mb-5">
                        @if($tenant->waterDepositPaid)
                            <button data-modal-target="WaterDepositModal">
                                <div class="flex items-center gap-3">
                                    <span class="size-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-600/20 dark:text-green-500">
                                        <i data-lucide="check" class="size-5"></i>
                                    </span>
                                    <div>
                                        <h6 class="text-lg font-bold text-green-600 dark:text-green-500">Water Deposit was fully paid</h6>
                                    </div>
                                </div>
                            </button>
                        @else
                            <button data-modal-target="DepositModal">
                                <div class="flex items-center gap-3">
                                    <span class="size-10 flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-600/20 dark:text-red-500">
                                        <i data-lucide="alert-triangle" class="size-5"></i>
                                    </span>
                                    <div>
                                        <h6 class="text-lg font-bold text-red-600 dark:text-red-500">Water Deposit not cleared</h6>
                                        <p class="text-sm text-slate-500 dark:text-zink-200">
                                            Water Deposit due: <strong>Ksh. {{ number_format($waterDepositBalance, 2) }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card bg-white dark:bg-zink-700 shadow-lg rounded-lg p-6">
                    <h6 class="mb-4 text-15 font-semibold text-slate-800 dark:text-zink-100">{{ $house->houseNo }} Payments Table</h6>
                    <!-- Filters -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label for="paymentTypeFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                            <select id="paymentTypeFilter" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                                <option value="all">All</option>
                                <option value="Rent">Rent</option>
                                <option value="Rent Deposit">Rent Deposit</option>
                                @foreach($paymentTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="startDateFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                            <input type="date" id="startDateFilter" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                        </div>
                        <div>
                            <label for="endDateFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                            <input type="date" id="endDateFilter" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                        </div>
                        <div class="flex items-end">
                            <button id="applyFilterBtn" class="btn bg-custom-500 text-white w-full">Apply Filters</button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap" id="paymentsTable">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-zink-600">
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Payment ID</th>
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Amount</th>
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Type</th>
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Method</th>
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Date Paid</th>
                                    <th class="px-4 py-2 font-medium text-slate-800 dark:text-zink-100 text-left">Narration</th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody">
                                <!-- Payments will be dynamically inserted here by JavaScript -->

                            </tbody>
                        </table>
                        <div id="noPaymentsMessage" class="hidden text-center py-4 text-slate-500 dark:text-zink-200">
                            No payments found for the selected filters.
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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
                        <input type="text" id="addHouseNo" name="houseNo" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" value="{{ $house->houseNo }}" readonly>
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
                        <label for="narration" class="inline-block mb-2 text-base font-medium">Narration</label>
                        <textarea id="narration" name="narration" class="form-textarea border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" rows="3" placeholder="Enter any additional details about the payment"></textarea>
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
<div id="DepositModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="depositLabel">Deposit Payment</h5>
            <button data-modal-close="DepositModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            @if($tenantDepositBalance > 0)
            <div class="space-y-1">
                @if($tenantDepositTotal == 0)
                <div class="flex items-center justify-center h-32">
                    <h6 class="text-lg font-bold text-red-600 dark:text-red-500">No deposit payments found</h6>
                </div>
                @else
                    @foreach($tenantDepositPayments as $tenantDepositPayment)
                    <div class="flex items-center justify-between py-2 border-b border-dashed border-slate-200 dark:border-zink-500">
                        <span class="text-slate-500 dark:text-zink-200">{{ $tenantDepositPayment->timePaid }}</span>
                        <span class="text-slate-800 dark:text-zink-100 font-semibold">Ksh. {{ $tenantDepositPayment->amount }}</span>
                    </div>
                    @endforeach
                @endif
            </div>
            @else
            <div class="flex items-center justify-center h-32">
                 <h6 class="text-lg font-bold text-green-600 dark:text-green-500">No deposit balance</h6>
            </div>
            @endif
        </div>
    </div>
</div>
<div id="WaterDepositModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="depositLabel">Water Deposit Payment</h5>
            <button data-modal-close="WaterDepositModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            @if($waterDepositBalance > 0)
            <div class="space-y-1">
                @if($waterDepositTotal == 0)
                <div class="flex items-center justify-center h-32">
                    <h6 class="text-lg font-bold text-red-600 dark:text-red-500">No deposit payments found</h6>
                </div>
                @else
                    @foreach($waterDepositPayments as $waterDepositPayment)
                    <div class="flex items-center justify-between py-2 border-b border-dashed border-slate-200 dark:border-zink-500">
                        <span class="text-slate-500 dark:text-zink-200">{{ $waterDepositPayment->timePaid }}</span>
                        <span class="text-slate-800 dark:text-zink-100 font-semibold">Ksh. {{ $waterDepositPayment->amount }}</span>
                    </div>
                    @endforeach
                @endif
            </div>
            @else
            <div class="flex items-center justify-center h-32">
                 <h6 class="text-lg font-bold text-green-600 dark:text-green-500">No water deposit balance</h6>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection