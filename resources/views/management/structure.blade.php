@extends('layouts.master')
@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Configure {{Session::get('rentalName')}} Structure</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">{{Session::get('rentalName')}} </a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Structure
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                <div class="xl:col-span-9">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">Add Structure</h6>
                            <form action="{{route('structure/addStructureType')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="structureType" class="inline-block mb-2 text-base font-medium">Add Structure</label>
                                            <input type="text" name="structureType" id="structureType" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Structure</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">Add Group</h6>
                            <form action="{{route('structure/addStructure')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="structureType" class="inline-block mb-2 text-base font-medium">Select Structure</label>
                                            <select
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices="" data-choices-search-false="" name="structureType" id="structureType">
                                                @foreach ($structureTypes as $structureType)
                                                    <option value="{{$structureType->structureType}}">{{$structureType->structureType}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <label for="structureName" class="inline-block mb-2 text-base font-medium">Add Grouping</label>
                                        <input type="text" name="structureName" id="structureName" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Grouping</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">Add House Type</h6>
                            <form action="{{route('structure/addHouseType')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="houseType" class="inline-block mb-2 text-base font-medium">Add house type</label>
                                            <input type="text" name="houseType" id="houseType" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <label for="price" class="inline-block mb-2 text-base font-medium">Set Price</label>
                                        <input type="text" name="price" id="price" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add House Type</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15 grow">Add House</h6>
                            <form action="{{route('addHouse')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-12">
                                    <div class="xl:col-span-6">
                                        <div>
                                            <label for="houseNo" class="inline-block mb-2 text-base font-medium">House Number</label>
                                            <input type="text" name="houseNo" id="houseNo" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <div>
                                        <label for="structureName" class="inline-block mb-2 text-base font-medium">Select Grouping</label>
                                            <select
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices="" data-choices-search-false="" name="structureName" id="structureName">
                                                @foreach ($structures as $structure)
                                                    <option value="{{$structure->structureName}}">{{$structure->structureName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 xl:col-span-12">
                                        <div>
                                        <label for="houseType" class="inline-block mb-2 text-base font-medium">Select House Type</label>
                                            <select
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                data-choices="" data-choices-search-false="" name="houseType" id="houseType">
                                                @foreach ($houseTypes as $houseType)
                                                    <option value="{{$houseType->houseType}}">{{$houseType->houseType}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6">
                                        <div>
                                            <input type="hidden" name="status" id="status" value="Vacant" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add House</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">Properties</h6>
                            <div>
                                <table class="w-full mb-0">
                                @foreach($rentals as $rental)
                                    <tbody>
                                        <tr>
                                        <td>
                                            <div class="rounded bg-slate-100 dark:bg-zink-500">
                                                    <img src="{{ $rental->rentalImage }}" alt="" class="w-12 h-12 rounded">
                                                </div>
                                            <div class="shrink-0">
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $rental->rentalName }}</a></td>
                                        <td>
                                        <div class="relative dropdown">
                                            <button class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50" id="projectDropdownmenu1" data-bs-toggle="dropdown">
                                                <i data-lucide="more-horizontal" class="size-4"></i>
                                            </button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]" aria-labelledby="projectDropdownmenu1">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500" href="{{route('rentals/selectProperty',['rentalNo' => $rental->rentalNo])}}">
                                                    <i data-lucide="eye" class="inline-block mr-1 size-3"></i> Select
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500" href="{{route('rentals/view/updateProperty', ['rentalNo' => $rental->rentalNo])}}">
                                                    <i data-lucide="file-edit" class="inline-block mr-1 size-3"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('rentals/deleteProperty', ['rentalNo' => $rental->rentalNo]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500">
                                                            <i data-lucide="trash-2" class="inline-block mr-1 size-3"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">Groupings</h6>
                            <div>
                                <table class="w-full mb-0">
                                @foreach($structures as $structure)
                                    <tbody>
                                        <tr>
                                        <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $structure->structureName }}</a></td>
                                        <td>
                                        <div class="relative dropdown">
                                            <button class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50" id="projectDropdownmenu1" data-bs-toggle="dropdown">
                                                <i data-lucide="more-horizontal" class="size-4"></i>
                                            </button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]" aria-labelledby="projectDropdownmenu1">
                                                <li>
                                                    <form action="{{ route('deleteStructure', ['structureName' => $structure->structureName]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500">
                                                            <i data-lucide="trash-2" class="inline-block mr-1 size-3"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">House Types</h6>
                            <div>
                                <table class="w-full mb-0">
                                @foreach($houseTypes as $houseType)
                                    <tbody>
                                        <tr>
                                        <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $houseType->houseType }}</a></td>
                                        <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $houseType->price }}</a></td>
                                        <td>
                                        <div class="relative dropdown">
                                            <button class="flex items-center justify-center size-[37.5px] dropdown-toggle p-0 text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100 active:text-slate-600 active:bg-slate-300 active:border-slate-300 active:ring active:ring-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:border-zink-600 dark:hover:border-zink-500 dark:text-zink-200 dark:ring-zink-400/50" id="projectDropdownmenu1" data-bs-toggle="dropdown">
                                                <i data-lucide="more-horizontal" class="size-4"></i>
                                            </button>
                                            <ul class="absolute z-50 hidden py-2 mt-1 text-left list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem]" aria-labelledby="projectDropdownmenu1">
                                                <li>
                                                    <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500" href="{{route('rentals/view/updateProperty', ['rentalNo' => $rental->rentalNo])}}">
                                                    <i data-lucide="file-edit" class="inline-block mr-1 size-3"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('deleteHouseType', ['houseType' => $houseType->houseType]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear bg-white text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500">
                                                            <i data-lucide="trash-2" class="inline-block mr-1 size-3"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4 text-15">Existing Houses</h6>
                            <div>
                                <table class="w-full mb-0">
                                @foreach($houses as $house)
                                    <tbody>
                                        <tr>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#">{{ $house->houseNo }}</a></td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#"> {{ $house->structureName }}</a></td>
                                            <td class="px-3.5 py-2.5 first:pl-0 last:pr-0 border-y border-transparent"><a href="#"> {{ $house->status }}</a></td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td><a href="{{route('management/houses/page')}}" class="text-slate-400 dark:text-zink-200">view more</td>
                                        </tr>
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