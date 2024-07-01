@extends('layouts.master')
@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">Add Tenant to {{ Session::get('rentalName') }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">Tenant Management</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Add Tenant
                </li>
            </ul>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
            <div class="xl:col-span-9">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15 grow">Add Tenant</h6>
                        <form action="{{ route('addTenant') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="houseNo" class="inline-block mb-2 text-base font-medium">House Number</label>
                                        <select name="houseNo" id="houseNo" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                            @foreach($houses as $house)
                                                <option value="{{ $house->houseNo }}">{{ $house->houseNo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="names" class="inline-block mb-2 text-base font-medium">Names</label>
                                        <input type="text" name="names" id="names" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="phoneNo" class="inline-block mb-2 text-base font-medium">Phone Number</label>
                                        <input type="text" name="phoneNo" id="phoneNo" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="IDNO" class="inline-block mb-2 text-base font-medium">ID Number</label>
                                        <input type="text" name="IDNO" id="IDNO" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="email" class="inline-block mb-2 text-base font-medium">Email</label>
                                        <input type="email" name="email" id="email" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <label for="img" class="inline-block mb-2 text-base font-medium">Image</label>
                                        <input type="file" name="img" id="img" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <input type="hidden" name="rentalNo" id="rentalNo" value="{{ $rentalNo }}" class="form-input">
                                    </div>
                                </div>
                                <div class="xl:col-span-6">
                                    <div>
                                        <input type="hidden" name="dateAdded" id="dateAdded" value="{{ now() }}" class="form-input">
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Tenant</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">Existing Tenants</h6>
                            <div>
                                <table class="w-full mb-0">
                                    <tbody>
                                    @foreach($tenants as $tenant)
                                        <tr>
                                            <td>
                                                <div class="rounded bg-slate-100 dark:bg-zink-500">
                                                    <img src="{{ $tenant->img }}" alt="" class="w-12 h-12 rounded">
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $tenant->names }}</a></td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $tenant->houseNo }}</a></td>       
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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