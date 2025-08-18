@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Clients</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{ Session::has('rentalName') ? Session::get('rentalName') : 'Select a property' }}</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Clients
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 xl:grid-cols-12 gap-x-5">
                <div class="lg:col-span-12 xl:col-span-3 xl:row-span-2">
                    <div class="mb-5">
                    <label for="clientsSelect" class="inline-block mb-2 text-base font-medium">Select Client</label>
                        <select class="form-input border-slate-200 focus:outline-none focus:border-custom-500" data-choices="" data-choices-search-false="" name="clientsSelect" id="clientsSelect">
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}" 
                                data-tenant-img="{{ $tenant->img ?? asset('assets/images/userDefault.png') }}" 
                                data-tenant-name="{{ $tenant->names }}" 
                                data-tenant-phone="0{{ $tenant->phoneNo }}" 
                                data-tenant-houseno="{{ $tenant->houseNo }}" 
                                data-tenant-ispaid="{{ $tenant->isPaid ? 'Paid' : 'Rent Due' }}" 
                                data-tenant-email="{{ $tenant->email }}" 
                                data-tenant-dateadded="{{ $tenant->dateAdded }}"
                                data-tenant-idno="{{ $tenant->IDNO }}">
                                {{ $tenant->names }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="card" id="tenant-details">
                        <div class="card-body">
                            <div class="text-center">
                                <h5>Tenant</h5>
                                @if($tenants->isEmpty())
                                    <p class="text-slate-500 dark:text-zink-200">No tenants available.</p>
                                @else
                                <div class="mx-auto rounded-full size-20 bg-slate-100 dark:bg-zink-600">
                                    <img src="{{ $tenants->first()->img ?? asset('assets/images/userDefault.png') }}" alt="" class="h-20 rounded-full">
                                </div>
                                <h6 class="mt-3 mb-1 text-16"><a href="#!" class="tenant-name">{{ $tenants->first()->names }}</a></h6>
                                <p class="text-slate-500 dark:text-zink-200 tenant-status">{{ $tenants->first()->isPaid ? 'Paid' : 'Rent Due' }}</p>
                                @endif
                            </div>
                            <div class="mt-5 overflow-x-auto">
                                <table class="w-full mb-0">
                                    <tbody>
                                        @if($tenants->isNotEmpty())
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200"> Phone Number:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold tenant-phone">0{{ $tenants->first()->phoneNo }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">House Number:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold tenant-houseno">{{ $tenants->first()->houseNo }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">ID Number</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold tenant-idno">{{ $tenants->first()->IDNO }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Email</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold tenant-email">{{ $tenants->first()->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Date Added</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold tenant-dateadded">{{ $tenants->first()->dateAdded }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Next Of Kin phone:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">...</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">No tenant details available.</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0"><i data-lucide="user-check-2"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $tenantsPaid }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Tenants Paid</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0"><i data-lucide="user-x-2"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $tenantsDue }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Tenants Rent Due</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center text-yellow-500 bg-yellow-100 rounded-md size-12 text-15 dark:bg-yellow-500/20 shrink-0"><i data-lucide="refresh-cw"></i></div>
                            <div class="grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $pendingPayments }}">0</span></h5>
                                <p class="text-slate-500 dark:text-zink-200">Pending Payments</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="xl:col-span-9 lg:col-span-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-4 mb-5 lg:grid-cols-2 xl:grid-cols-12">
                                <div class="xl:col-span-3">
                                    <div class="relative">
                                        <input type="text" class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Search for ..." autocomplete="off">
                                        <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                                    </div>
                                </div><!--end col-->
                                <div class="flex justify-end gap-2 text-right lg:col-span-2 xl:col-span-4 xl:col-start-10">
                                    <div class="shrink-0">
                                    <form id="broadcast-sms-form" action="{{ route('sendBroadcast') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="recipients" value="0708681664,0701234567"> <!-- Example recipients -->
                                        <input type="hidden" name="message" value="KIND REMINDER TO PAY YOUR RENT">
                                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                                            <i data-lucide="mail" class="inline-block size-4"></i> 
                                            <span class="align-middle">BROADCAST SMS</span>
                                        </a>
                                    </form>
                                    </div>
                                    <div class="shrink-0">
                                        <a href="{{route('clients/add')}}" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                                            <i data-lucide="plus" class="inline-block size-4"></i> 
                                            <span class="align-middle">Add Tenant</span>
                                        </a>
                                    </div>
                                </div>
                            </div><!--end grid-->
                            <div class="overflow-x-auto">
                                <table class="w-full whitespace-nowrap">
                                    <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                        <tr>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Names</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Phone Number</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">House Number</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Payment Status</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500 ltr:text-right rtl:text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tenants as $tenant)
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500 Name">
                                                <a href="#!" class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full shrink-0 bg-slate-100">
                                                        <img src="{{ $tenant->img ?? asset('assets/images/userDefault.png') }}" alt="" class="h-6 rounded-full">
                                                    </div>
                                                    <h6 class="grow">{{ $tenant->names }}</h6>
                                                </a>
                                            </td>
                                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500 Role">
                                                0{{ $tenant->phoneNo }}
                                            </td>
                                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500 Role">
                                                {{ $tenant->houseNo }}
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                @if ($tenant->isPaid)
                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-green-100 border-transparent text-green-500 dark:bg-green-500/20 dark:border-transparent">Paid</span>
                                                @else
                                                <span class="px-2.5 py-0.5 inline-block text-xs font-medium rounded border bg-red-100 border-transparent text-red-500 dark:bg-red-500/20 dark:border-transparent">Unpaid</span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex justify-end gap-2">
                                                    <a class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500" data-tenant-id="{{ $tenant->id }}" href="#" 
                                                        data-tenant-name="{{ $tenant->names }}" 
                                                        data-tenant-phone="0{{ $tenant->phoneNo }}" 
                                                        data-tenant-houseno="{{ $tenant->houseNo }}" 
                                                        data-tenant-img="{{ $tenant->img ?? asset('assets/images/userDefault.png')}}" 
                                                        data-tenant-ispaid="{{ $tenant->isPaid ? 'Paid' : 'Rent Due' }}" 
                                                        data-tenant-email="{{ $tenant->email }}" 
                                                        data-tenant-dateadded="{{ $tenant->dateAdded }}"
                                                        data-tenant-idno="{{ $tenant->IDNO }}">
                                                        <i data-lucide="eye" class="inline-block size-3"></i>
                                                    </a>
                                                    <a href="#" data-modal-target="editClientModal-{{ $tenant->tenantNo }}" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 edit-item-btn bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500">
                                                        <i data-lucide="pencil" class="size-4"></i>
                                                    </a>
                                                    <a href="#" data-modal-target="deleteClientModal-{{ $tenant->tenantNo }}" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 remove-item-btn bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500">
                                                        <i data-lucide="trash-2" class="size-4"></i>
                                                    </a>  
                                                </div>
                                            </td>
                                        </tr>
                                        <div id="editClientModal-{{ $tenant->tenantNo }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                            <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editClientModal-{{ $tenant->tenantNo }}">
                                                <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                                    <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Tenant Record</h5>
                                                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editClientModal-{{ $tenant->tenantNo }}">
                                                        <i data-lucide="x" class="size-4"></i>
                                                    </button>
                                                </div>
                                                <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                    <form id="updateClientForm-{{ $tenant->tenantNo }}" action="{{ route('updateClient', ['tenantNo' => $tenant->tenantNo]) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                            <div class="xl:col-span-12">
                                                                <div
                                                                    class="relative mx-auto mb-4 rounded-full shadow-md size-24 bg-slate-100 profile-user dark:bg-zink-500">
                                                                        <img src="{{ $tenant->img ?? asset('assets/images/userDefault.png') }}" 
                                                                        alt="" class="object-cover w-full h-full rounded-full user-profile-image">
                                                                    <div
                                                                        class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0 profile-photo-edit">
                                                                        <input id="img" name="img" type="file">
                                                                        <label for="profile-img-file-input"
                                                                            class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600 profile-photo-edit">
                                                                            <i data-lucide="image-plus"
                                                                                class="size-4 text-slate-500 fill-slate-200 dark:text-zink-200 dark:fill-zink-500"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="xl:col-span-6">
                                                                <div>
                                                                    <label for="names" class="inline-block mb-2 text-base font-medium">Full Name</label>
                                                                    <input type="text" name="names" id="names" value="{{ $tenant->names }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                                </div>
                                                            </div>
                                                            <div class="xl:col-span-6">
                                                                <div>
                                                                    <label for="phoneNo" class="inline-block mb-2 text-base font-medium">Phone Number</label>
                                                                    <input type="text" name="phoneNo" id="phoneNo" value="0{{ $tenant->phoneNo }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                                </div>
                                                            </div>
                                                            <div class="md:col-span-2 xl:col-span-12">
                                                                <div>
                                                                    <label for="IDNO" class="inline-block mb-2 text-base font-medium">ID Number</label>
                                                                    <input type="text" name="IDNO" id="IDNO" value="{{ $tenant->IDNO }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                                </div>
                                                            </div>
                                                            <div class="md:col-span-2 xl:col-span-12">
                                                                <div>
                                                                    <label for="email" class="inline-block mb-2 text-base font-medium">Email</label>
                                                                    <input type="email" name="email" id="email" value="{{ $tenant->email }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                                </div>
                                                            </div>
                                                            <div class="md:col-span-2 xl:col-span-12">
                                                                <div>
                                                                    <label for="dateAdded" class="inline-block mb-2 text-base font-medium">Date of Joining</label>
                                                                    <input type="date" name="dateAdded" id="dateAdded" value="{{ $tenant->dateAdded }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end gap-2 mt-4">
                                                            <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Tenant</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Delete Modal --}}
                                        <div id="deleteClientModal-{{ $tenant->tenantNo }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                            <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                                                <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                                                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                                        <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                                        <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deleteClientModal-{{ $tenant->tenantNo }}">
                                                            <i data-lucide="x" class="size-4"></i>
                                                        </button>
                                                    </div>
                                                    <div class="p-4 overflow-y-auto">
                                                        <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete this tenant record for <span class="font-semibold text-red-500">{{ $tenant->names }}</span>? <span class="font-semibold text-red-500">This action cannot be undone!</span></p>
                                                        <div class="flex justify-end gap-2 mt-4">
                                                            <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deleteClientModal-{{ $tenant->tenantNo }}">Cancel</button>
                                                            <form id="deleteClientForm-{{ $tenant->tenantNo }}" method="POST" action="{{ route('deleteClient', ['tenantNo' => $tenant->tenantNo]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn bg-red-500 text-white hover:bg-red-600">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex flex-col items-center mt-5 md:flex-row">
                                <div class="mb-4 grow md:mb-0">
                                    <p class="text-slate-500 dark:text-zink-200">Showing <b>10</b> of <b>15</b> Results</p>
                                </div>
                                <ul class="flex flex-wrap items-center gap-2 shrink-0">
                                    <li>
                                        <a href="#!" class="inline-flex items-center justify-center bg-white dark:bg-zink-700 h-8 px-3 transition-all duration-150 ease-linear border rounded border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-500 dark:[&.active]:text-custom-500 [&.active]:bg-custom-50 dark:[&.active]:bg-custom-500/10 [&.active]:border-custom-50 dark:[&.active]:border-custom-500/10 [&.active]:hover:text-custom-700 dark:[&.active]:hover:text-custom-700 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto"><i class="mr-1 size-4 rtl:rotate-180" data-lucide="chevron-left"></i> Prev</a>
                                    </li>
                                    <li>
                                        <a href="#!" class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border rounded border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-500 dark:[&.active]:text-custom-500 [&.active]:bg-custom-50 dark:[&.active]:bg-custom-500/10 [&.active]:border-custom-50 dark:[&.active]:border-custom-500/10 [&.active]:hover:text-custom-700 dark:[&.active]:hover:text-custom-700 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">1</a>
                                    </li>
                                    <li>
                                        <a href="#!" class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border rounded border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-500 dark:[&.active]:text-custom-500 [&.active]:bg-custom-50 dark:[&.active]:bg-custom-500/10 [&.active]:border-custom-50 dark:[&.active]:border-custom-500/10 [&.active]:hover:text-custom-700 dark:[&.active]:hover:text-custom-700 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto active">2</a>
                                    </li>
                                    <li>
                                        <a href="#!" class="inline-flex items-center justify-center bg-white dark:bg-zink-700 size-8 transition-all duration-150 ease-linear border rounded border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-500 dark:[&.active]:text-custom-500 [&.active]:bg-custom-50 dark:[&.active]:bg-custom-500/10 [&.active]:border-custom-50 dark:[&.active]:border-custom-500/10 [&.active]:hover:text-custom-700 dark:[&.active]:hover:text-custom-700 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">3</a>
                                    </li>
                                    <li>
                                        <a href="#!" class="inline-flex items-center justify-center bg-white dark:bg-zink-700 h-8 px-3 transition-all duration-150 ease-linear border rounded border-slate-200 dark:border-zink-500 text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500 hover:bg-custom-50 dark:hover:bg-custom-500/10 focus:bg-custom-50 dark:focus:bg-custom-500/10 focus:text-custom-500 dark:focus:text-custom-500 [&.active]:text-custom-500 dark:[&.active]:text-custom-500 [&.active]:bg-custom-50 dark:[&.active]:bg-custom-500/10 [&.active]:border-custom-50 dark:[&.active]:border-custom-500/10 [&.active]:hover:text-custom-700 dark:[&.active]:hover:text-custom-700 [&.disabled]:text-slate-400 dark:[&.disabled]:text-zink-300 [&.disabled]:cursor-auto">Next <i class="ml-1 size-4 rtl:rotate-180" data-lucide="chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end grid-->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ URL::to('assets/js/tenantCard.js') }}"></script>
@endsection
@endsection