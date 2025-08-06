@extends('layouts.master')

@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Breadcrumb -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Reservations</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ Session::has('rentalName') ? Session::get('rentalName') : 'Select a property' }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Reservations
                    </li>
                </ul>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-blue-500 bg-blue-100 rounded-md size-12 text-15 dark:bg-blue-500/20 shrink-0">
                            <i data-lucide="calendar-check"></i>
                        </div>
                        <div class="overflow-hidden grow">
                            <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $totalReservations ?? 0 }}">0</span></h5>
                            <p class="truncate text-slate-500 dark:text-zink-200">Total Reservations</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div class="overflow-hidden grow">
                            <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $confirmedReservations ?? 0 }}">0</span></h5>
                            <p class="truncate text-slate-500 dark:text-zink-200">Confirmed</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-yellow-500 bg-yellow-100 rounded-md size-12 text-15 dark:bg-yellow-500/20 shrink-0">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="overflow-hidden grow">
                            <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $pendingReservations ?? 0 }}">0</span></h5>
                            <p class="truncate text-slate-500 dark:text-zink-200">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="flex items-center gap-4 card-body">
                        <div class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0">
                            <i data-lucide="calendar-x"></i>
                        </div>
                        <div class="overflow-hidden grow">
                            <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $expiredReservations ?? 0 }}">0</span></h5>
                            <p class="truncate text-slate-500 dark:text-zink-200">Expired</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 xl:grid-cols-12 gap-x-5">
                
                <!-- Add Reservation Form -->
                <div class="lg:col-span-12 xl:col-span-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">Add New Reservation</h6>
                            <form action="{{ route('page/reservations/add') }}" method="POST" id="reservationForm">
                                @csrf
                                
                                <!-- Client Details Section -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-sm font-semibold text-slate-600 dark:text-zink-200">Client Information</h6>
                                    
                                    <div class="mb-3">
                                        <label for="clientName" class="inline-block mb-2 text-base font-medium">Client Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="clientName" name="client_name" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Enter client full name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="clientPhone" class="inline-block mb-2 text-base font-medium">Phone Number <span class="text-red-500">*</span></label>
                                        <input type="tel" id="clientPhone" name="client_phone" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="0712345678" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="clientEmail" class="inline-block mb-2 text-base font-medium">Email Address</label>
                                        <input type="email" id="clientEmail" name="client_email" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="client@example.com">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="clientIdNo" class="inline-block mb-2 text-base font-medium">ID Number</label>
                                        <input type="text" id="clientIdNo" name="client_id_no" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="12345678">
                                    </div>
                                </div>

                                <!-- Reservation Details Section -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-sm font-semibold text-slate-600 dark:text-zink-200">Reservation Details</h6>
                                    
                                    <div class="mb-3">
                                        <label for="houseNumber" class="inline-block mb-2 text-base font-medium">House Number <span class="text-red-500">*</span></label>
                                        <select 
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" name="house_number" id="houseNumber" required>
                                            <option value="">Select House Number</option>
                                            @if(isset($availableHouses))
                                                @foreach($availableHouses as $house)
                                                    <option value="{{ $house->houseNo }}">House {{ $house->houseNo }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="entryDate" class="inline-block mb-2 text-base font-medium">Expected Entry Date <span class="text-red-500">*</span></label>
                                        <input type="date" id="entryDate" name="entry_date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reservationStatus" class="inline-block mb-2 text-base font-medium">Status</label>
                                        <select class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" name="status" id="reservationStatus">
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="notes" class="inline-block mb-2 text-base font-medium">Notes</label>
                                        <textarea id="notes" name="notes" rows="3" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Additional notes or requirements..."></textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="reset" class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-500 hover:bg-slate-50 hover:border-slate-300 focus:text-slate-500 focus:bg-slate-50 focus:border-slate-300 active:text-slate-500 active:bg-slate-50 active:border-slate-300 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10">
                                        <i data-lucide="x" class="inline-block size-4 mr-1"></i>
                                        Clear
                                    </button>
                                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                        <i data-lucide="plus" class="inline-block size-4 mr-1"></i>
                                        Add Reservation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reservations List -->
                <div class="lg:col-span-12 xl:col-span-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-2 xl:grid-cols-12">
                                <div class="xl:col-span-3">
                                    <div class="relative">
                                        <input type="text" class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Search reservations..." autocomplete="off">
                                        <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                                    </div>
                                </div>
                                
                                <div class="xl:col-span-3">
                                    <select class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                
                                <div class="flex justify-end gap-2 text-right lg:col-span-2 xl:col-span-6 xl:col-start-7">
                                    <div class="shrink-0">
                                        <button type="button" class="text-slate-500 bg-white border-slate-300 btn hover:text-slate-500 hover:bg-slate-50 hover:border-slate-300 focus:text-slate-500 focus:bg-slate-50 focus:border-slate-300 active:text-slate-500 active:bg-slate-50 active:border-slate-300 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10">
                                            <i data-lucide="download" class="inline-block size-4 mr-1"></i>
                                            Export
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full whitespace-nowrap">
                                    <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                        <tr>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">House Number</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Client Name</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Phone Number</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Entry Date</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Status</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 ltr:text-right rtl:text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="reservationList">
                                        @if(isset($reservations) && $reservations->count() > 0)
                                            @foreach ($reservations as $reservation)
                                                <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                                        {{ $reservation->houseNo }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                                        {{ $reservation->clientName }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                                        0{{ $reservation->clientPhoneNo }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                                        {{ $reservation->reservationDate }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                        @switch($reservation->status)
                                                            @case('confirmed')
                                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-green-100 border-transparent text-green-500 dark:bg-green-500/20 dark:border-transparent">Confirmed</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-red-100 border-transparent text-red-500 dark:bg-red-500/20 dark:border-transparent">Cancelled</span>
                                                                @break
                                                            @default
                                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-yellow-100 border-transparent text-yellow-500 dark:bg-yellow-500/20 dark:border-transparent">Pending</span>
                                                        @endswitch
                                                    </td>
                                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500 ltr:text-right rtl:text-left">
                                                        <div class="flex gap-2">
                                                            <button data-modal-target="editReservationModal-{{ $reservation->reservationID }}" class="edit-reservation-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">
                                                                <i data-lucide="pencil" class="size-4"></i>
                                                            </button>
                                                            <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                                Edit
                                                            </div>
                                                            <button type="button" data-modal-target="deleteModal-{{ $reservation->reservationID }}" class="delete-reservation-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">
                                                                <i data-lucide="trash-2" class="size-4"></i>
                                                            </button>
                                                            <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                                Delete
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <div id="editReservationModal-{{ $reservation->reservationID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editReservationModal-{{ $reservation->reservationID }}">
                                                        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                                            <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Reservation</h5>
                                                            <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editReservationModal-{{ $reservation->reservationID }}">
                                                                <i data-lucide="x" class="size-4"></i>
                                                            </button>
                                                        </div>
                                                        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                            <form id="updateReservationForm-{{ $reservation->reservationID }}" method="POST" action="{{ route('page/reservations/update', ['reservationID' => $reservation->reservationID]) }}">
                                                                @csrf
                                                                <div class="mb-4">
                                                                    <h6 class="mb-3 text-sm font-semibold text-slate-600 dark:text-zink-200">Client Information</h6>
                                                                    <div class="mb-3">
                                                                        <label for="clientName" class="inline-block mb-2 text-base font-medium">Client Name <span class="text-red-500">*</span></label>
                                                                        <input type="text" id="editClientName-{{ $reservation->reservationID }}" name="client_name" value="{{ $reservation->clientName }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="Enter client full name" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="clientPhone" class="inline-block mb-2 text-base font-medium">Phone Number <span class="text-red-500">*</span></label>
                                                                        <input type="tel" id="editClientPhone-{{ $reservation->reservationID }}" name="client_phone" value="{{ $reservation->clientPhone }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="0712345678" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="clientEmail" class="inline-block mb-2 text-base font-medium">Email Address</label>
                                                                        <input type="email" id="editClientEmail-{{ $reservation->reservationID }}" name="client_email" value="{{ $reservation->clientEmail }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="client@example.com">
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="clientIdNo" class="inline-block mb-2 text-base font-medium">ID Number</label>
                                                                        <input type="text" id="editClientIdNo-{{ $reservation->reservationID }}" name="client_id_no" value="{{ $reservation->clientIdNo }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="12345678">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <h6 class="mb-3 text-sm font-semibold text-slate-600 dark:text-zink-200">Reservation Details</h6>
                                                                    <div class="mb-3">
                                                                        <label for="houseNumber" class="inline-block mb-2 text-base font-medium">House Number <span class="text-red-500">*</span></label>
                                                                        <select class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" name="house_number" id="editHouseNumber-{{ $reservation->reservationID }}" required>
                                                                            <option value="{{ $reservation->houseNumber }}" selected>{{ $reservation->houseNumber }}</option>
                                                                            @foreach ($availableHouses as $house)
                                                                                <option value="{{ $house->houseNo }}">{{ $house->houseNo }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="reservationDate" class="inline-block mb-2 text-base font-medium">Reservation Date <span class="text-red-500">*</span></label>
                                                                        <input type="date" id="editReservationDate-{{ $reservation->reservationID }}" name="reservation_date" value="{{ $reservation->reservationDate }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="status" class="inline-block mb-2 text-base font-medium">Status <span class="text-red-500">*</span></label>
                                                                        <select name="status" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="editStatus-{{ $reservation->reservationID }}" required>
                                                                            <option value="Pending" @if($reservation->status == 'Pending') selected @endif>Pending</option>
                                                                            <option value="Confirmed" @if($reservation->status == 'Confirmed') selected @endif>Confirmed</option>
                                                                            <option value="Expired" @if($reservation->status == 'Expired') selected @endif>Expired</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="reservationNotes" class="inline-block mb-2 text-base font-medium">Notes</label>
                                                                        <textarea id="editReservationNotes-{{ $reservation->reservationID }}" name="notes" rows="3" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">{{ $reservation->notes }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="w-full text-white btn bg-yellow-500 border-yellow-500 hover:bg-yellow-600">Update Reservation</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="deleteModal-{{ $reservation->reservationID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                                    <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                                                        <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                                                            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                                                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deleteModal-{{ $reservation->reservationID }}">
                                                                    <i data-lucide="x" class="size-4"></i>
                                                                </button>
                                                            </div>
                                                            <div class="p-4 overflow-y-auto">
                                                                <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the reservation for <span id="deleteClientName" class="font-semibold text-red-500">{{ $reservation->clientName }}</span>?</p>
                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deleteModal-{{ $reservation->reservationID }}">Cancel</button>
                                                                    <form id="deleteReservationForm-{{ $reservation->reservationID }}" method="POST" action="{{ route('page/reservations/delete', ['reservationID' => $reservation->reservationID]) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <input type="hidden" name="reservationID" value="{{ $reservation->reservationID }}">
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
                                                <td colspan="6" class="text-center py-4">No reservations found.</td>
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

    <!-- Add Reservation Modal -->
    <div id="addReservationModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-beSTEen p-4 border-b dark:border-zink-500">
                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Add New Reservation</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="addReservationModal">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form action="{{ route('page/reservations/add') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="newClientName" class="inline-block mb-2 text-base font-medium">Client Name <span class="text-red-500">*</span></label>
                        <input type="text" id="newClientName" name="client_name" class="form-input" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newClientPhone" class="inline-block mb-2 text-base font-medium">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="newClientPhone" name="client_phone" class="form-input" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newClientEmail" class="inline-block mb-2 text-base font-medium">Email Address</label>
                        <input type="email" id="newClientEmail" name="client_email" class="form-input">
                    </div>
                    
                    <div class="mb-3">
                        <label for="newClientIdNo" class="inline-block mb-2 text-base font-medium">ID Number</label>
                        <input type="text" id="newClientIdNo" name="client_id_no" class="form-input">
                    </div>

                    <div class="mb-3">
                        <label for="newHouseNumber" class="inline-block mb-2 text-base font-medium">House Number <span class="text-red-500">*</span></label>
                        <select class="form-input" name="house_number" id="newHouseNumber" required>
                            <option value="">Select House Number</option>
                            @if(isset($availableHouses))
                                @foreach($availableHouses as $house)
                                    <option value="{{ $house->houseNo }}">House {{ $house->houseNo }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newEntryDate" class="inline-block mb-2 text-base font-medium">Expected Entry Date <span class="text-red-500">*</span></label>
                        <input type="date" id="newEntryDate" name="entry_date" class="form-input" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newReservationStatus" class="inline-block mb-2 text-base font-medium">Status</label>
                        <select class="form-input" name="status" id="newReservationStatus">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="newNotes" class="inline-block mb-2 text-base font-medium">Notes</label>
                        <textarea id="newNotes" name="notes" rows="3" class="form-input" placeholder="Additional notes or requirements..."></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="addReservationModal">Cancel</button>
                        <button type="submit" class="btn bg-blue-500 text-white hover:bg-blue-600">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            document.querySelectorAll('[data-modal-target]').forEach(function(trigger) {
                trigger.addEventListener('click', function() {
                    var targetModal = document.getElementById(this.getAttribute('data-modal-target'));
                    if (targetModal) {
                        targetModal.classList.remove('hidden');
                    }
                });
            });

            document.querySelectorAll('[data-modal-close]').forEach(function(closeBtn) {
                closeBtn.addEventListener('click', function() {
                    var targetModal = document.getElementById(this.getAttribute('data-modal-close'));
                    if (targetModal) {
                        targetModal.classList.add('hidden');
                    }
                });
            });

            // Handle edit button click
            document.querySelectorAll('.edit-reservation-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    const reservationId = event.currentTarget.getAttribute('data-reservation-id');
                    
                    // Fetch reservation data
                    fetch(`/reservations/${reservationId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the edit modal form
                            document.getElementById('editClientName').value = data.client_name;
                            document.getElementById('editClientPhone').value = data.client_phone;
                            document.getElementById('editClientEmail').value = data.client_email;
                            document.getElementById('editClientIdNo').value = data.client_id_no;
                            document.getElementById('editHouseNumber').value = data.houseNo;
                            document.getElementById('editEntryDate').value = data.reservationDate;
                            document.getElementById('editReservationStatus').value = data.status;
                            document.getElementById('editNotes').value = data.notes;

                            // Update form action URL dynamically
                            const editForm = document.getElementById('editReservationForm');
                            editForm.action = `{{ url('page/reservations/update') }}/${reservationId}`;
                        })
                        .catch(error => console.error('Error fetching reservation data:', error));
                });
            });

            // Handle delete button click
            document.querySelectorAll('.delete-reservation-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    const reservationId = event.currentTarget.getAttribute('data-reservation-id');
                    const clientName = event.currentTarget.getAttribute('data-client-name');
                    
                    document.getElementById('deleteClientName').textContent = clientName;
                    document.getElementById('deleteReservationId').value = reservationId;
                    document.getElementById('deleteReservationForm').action = `{{ url('page/reservations/delete') }}/${reservationId}`;
                });
            });

            // Set minimum date to today for add and edit forms
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('entryDate').setAttribute('min', today);
            document.getElementById('newEntryDate').setAttribute('min', today);
            document.getElementById('editEntryDate').setAttribute('min', today);
        });
    </script>
@endsection
