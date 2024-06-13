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
                        <label for="deliveryStatusSelect" class="inline-block mb-2 text-base font-medium">Select Client</label>
                        <select class="form-input border-slate-200 focus:outline-none focus:border-custom-500" data-choices="" data-choices-search-false="" name="deliveryStatusSelect" id="deliveryStatusSelect">
                            <option value="">Select Client</option>
                            <option value="Willie Torres">Willie Torres</option>
                            <option value="Patricia Garcia">Patricia Garcia</option>
                            <option value="Juliette Fecteau">Juliette Fecteau</option>
                            <option value="Thomas Hatfield">Thomas Hatfield</option>
                            <option value="Juliette Fecteau">Juliette Fecteau</option>
                            <option value="Nancy Reynolds">Nancy Reynolds</option>
                            <option value="Holly Kavanaugh">Holly Kavanaugh</option>
                            <option value="Jonas Frederiksen">Jonas Frederiksen</option>
                        </select>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h5>Tenant</h5>
                                <div class="mx-auto rounded-full size-20 bg-slate-100 dark:bg-zink-600">
                                    <img src="{{ URL::to('assets/images/profile.png') }}" alt="" class="h-20 rounded-full">
                                </div>
                                <h6 class="mt-3 mb-1 text-16"><a href="#!">Names</a></h6>
                                <p class="text-slate-500 dark:text-zink-200">is Paid?</p>
                            </div>
                            <div class="mt-5 overflow-x-auto">
                                <table class="w-full mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200"> Phone Number:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">013190128</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">House Number:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">#TWE1001501</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">ID Number</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">019281229</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Email</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">soung@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Date Added</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">1-12-1999</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent text-slate-500 dark:text-zink-200">Next Of Kin phone:</td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent font-semibold">019123891</td>
                                        </tr>
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
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="32">0</span></h5>
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
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="6">0</span></h5>
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
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="15">0</span></h5>
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
                                        <a href="#!" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
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
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Date</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Check In</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Check Out</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Meal Break</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Work Hours</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Overtime</th>
                                            <th class="px-3.5 py-2.5 font-semibold border-b border-slate-200 dark:border-zink-500">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                13 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Fri</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:23 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">07:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">1.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                12 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Thu</span> 
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:28 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:46 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.45 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                11 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Wed</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">09:00 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">06:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.50 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.35 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                10 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Tue</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">09:15 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.35 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">7.55 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                09 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Mon</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:28 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:46 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.45 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                06 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Fri</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">09:00 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">06:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.50 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.35 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                05 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Thu</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:28 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:46 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.45 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                04 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Wed</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:28 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:46 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.45 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                03 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Tue</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">08:23 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">07:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">1.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">8.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.00 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                02 Oct, 2023 <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-white border-slate-400 text-slate-500 dark:bg-zink-700 dark:border-zink-400 dark:text-zink-200 ltr:ml-1 rtl:mr-1 align-middle">Mon</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">09:15 AM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">05:00 PM</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.35 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">7.55 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">0.15 Hrs</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">
                                                <div class="flex gap-2">
                                                    <a href="#!" class="flex items-center justify-center text-green-500 transition-all duration-200 ease-linear bg-green-100 rounded-md size-8 hover:text-white hover:bg-green-500 dark:bg-green-500/20 dark:hover:bg-green-500"><i data-lucide="check" class="size-4"></i></a>
                                                    <a href="#!" class="flex items-center justify-center text-red-500 transition-all duration-200 ease-linear bg-red-100 rounded-md size-8 hover:text-white hover:bg-red-500 dark:bg-red-500/20 dark:hover:bg-red-500"><i data-lucide="x" class="size-4"></i></a>
                                                </div>
                                            </td>
                                        </tr>
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

@endsection
@endsection