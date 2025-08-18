@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[layout=horizontal]:max-w-full">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center">
                <div class="grow">
                    <h5 class="text-16">Deleted Tenants</h5>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12">
                    <div class="card dark:bg-zink-600">
                        <div class="card-body">
                            <div class="overflow-x-auto">
                                <table class="w-full whitespace-nowrap" id="deletedTenantsTable">
                                    <thead>
                                        <tr class="group bg-slate-50 dark:bg-zink-600">
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">Deleted Tenant No</th>
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">House No</th>
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">Full Name</th>
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">Phone No</th>
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">Debt</th>
                                            <th class="px-3.5 py-2.5 font-semibold text-left border-y border-slate-200 dark:border-zink-500">Date Deleted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($deletedTenants as $deletedTenant)
                                        <tr>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">{{ $deletedTenant->deletedTenantNo }}</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">{{ $deletedTenant->houseNo }}</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">{{ $deletedTenant->names }}</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">{{ $deletedTenant->phoneNo }}</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">KSh {{ number_format($deletedTenant->debt, 2) }}</td>
                                            <td class="px-3.5 py-2.5 border-y border-slate-200 dark:border-zink-500">{{ \Carbon\Carbon::parse($deletedTenant->dateDeleted)->format('Y-m-d') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-end mt-4">
                                <!-- Pagination links can be added here if needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection