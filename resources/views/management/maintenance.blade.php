@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Initial data for the chart, passed from the controller
    var initialChartLabels = @json($months);
    var initialChartData = @json($amounts);
    //For filtering
    var filterRoute = '{{ route("maintenance/filter") }}';
</script>

<script src="{{ URL::to('assets/js/pages/maintenance.js') }}"></script>

<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">Maintenance {{ Session::get('rentalName') }}</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200"> {{ Session::get('rentalName') }} Manage</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Maintenance
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="mb-4 text-15">Maintenance Filters</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-12 gap-5">
                    <div class="xl:col-span-4">
                        <label for="houseSelect" class="inline-block mb-2 text-base font-medium">Select House</label>
                        <select class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            data-choices="" data-choices-search-false="" name="houseSelect" id="houseSelect">
                            <option value="all">All Houses</option>
                            @foreach ($houses as $house)
                                <option value="{{ $house->houseNo }}">{{ $house->houseNo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-4">
                        <label for="startDate" class="inline-block mb-2 text-base font-medium">From Date</label>
                        <input type="date" name="startDate" id="startDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:focus:border-custom-800">
                    </div>
                    <div class="xl:col-span-4">
                        <label for="endDate" class="inline-block mb-2 text-base font-medium">To Date</label>
                        <input type="date" name="endDate" id="endDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:focus:border-custom-800">
                    </div>
                    <div class="col-span-12">
                        <div class="flex justify-end mt-4">
                            <button type="button" id="filterButton" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-x-5 mt-5">
            <div class="col-span-12 lg:col-span-12 xl:col-span-4 card">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Total Maintenance Expenditure</h6>
                    <div class="flex items-center">
                        <h4 class="mb-0 text-3xl font-semibold"><span id="totalExpenditureDisplay" class="counter-value" data-target="{{ $totalExpenditure }}"></span></h4>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-12 xl:col-span-8 card">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Maintenance Expenditure over Time</h6>
                    <div id="maintenanceChart" class="apex-charts" data-chart-colors='["bg-red-500"]'></div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="text-15 grow">Maintenance Records</h6>
                        <div class="shrink-0"></div>
                        <div class="shrink-0">
                            <a href="#!" data-modal-target="addMaintenanceModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                                <i data-lucide="plus" class="inline-block size-4"></i> 
                                <span class="align-middle">Add Record</span>
                            </a>
                        </div>
                    </div>
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-zink-600">
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold text-sm text-left">House No</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold text-sm text-left">Date</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold text-sm text-left">Description</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold text-sm text-left">Amount</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold text-sm text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody id="maintenanceDetailsBody">
                            @if(isset($maintenances) && $maintenances->count() > 0)
                            @foreach ($maintenances as $maintenance)
                                <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                                    <td class="px-4 py-2">{{ $maintenance->houseNo }}</td>
                                    <td class="px-4 py-2">{{ $maintenance->maintenanceDate }}</td>
                                    <td class="px-4 py-2">{{ $maintenance->maintenanceDescription }}</td>
                                    <td class="px-4 py-2">{{ number_format($maintenance->amount, 2) }}</td>
                                    <td class="px-4 py-2 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500 ltr:text-right rtl:text-left">
                                        <div class="flex items-center gap-2">
                                            <div class="relative group">
                                                <button type="button" data-modal-target="editMaintenanceModal-{{ $maintenance->maintenanceNo }}" class="edit-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">
                                                    <i data-lucide="edit-2" class="size-4"></i>
                                                </button>
                                                <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                    Edit
                                                </div>
                                            </div>
                                            <div class="relative group">
                                                <button type="button" data-modal-target="deleteMaintenanceModal-{{ $maintenance->maintenanceNo }}" class="delete-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </button>
                                                <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                    Delete
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <div id="editMaintenanceModal-{{ $maintenance->maintenanceNo }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editMaintenanceModal-{{ $maintenance->maintenanceNo }}">
                                        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                            <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Maintenance Record</h5>
                                            <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editMaintenanceModal-{{ $maintenance->maintenanceNo }}">
                                                <i data-lucide="x" class="size-4"></i>
                                            </button>
                                        </div>
                                        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                            <form id="updateMaintenanceForm-{{ $maintenance->maintenanceNo }}" action="{{ route('maintenance/update/{maintenanceNo}', ['maintenanceNo' => $maintenance->maintenanceNo]) }}" method="POST">
                                                @csrf
                                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                    <div>
                                                        <label for="updateHouseNo" class="inline-block mb-2 text-base font-medium">Select House</label>
                                                        <select name="houseNo" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateHouseNo-{{ $maintenance->maintenanceNo }}" required>
                                                            <option value="{{ $maintenance->houseNo }}" selected>{{ $maintenance->houseNo }}</option>
                                                            @foreach ($houses as $house)
                                                                <option value="{{ $house->houseNo }}">{{ $house->houseNo }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="addDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                                        <input type="date" name="date" id="addDate-{{ $maintenance->maintenanceNo }}" value="{{ $maintenance->maintenanceDate }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label for="addDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                                        <input type="text" name="description" value="{{ $maintenance->maintenanceDescription }}" id="addDescription-{{ $maintenance->maintenanceNo }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. Broken pipe" required>
                                                    </div>
                                                    <div>
                                                        <label for="addAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                                                        <input type="number" step="0.01" name="amount" value="{{ $maintenance->amount }}" id="addAmount-{{ $maintenance->maintenanceNo }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label for="addNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                                        <textarea id="addNotes-{{ $maintenance->maintenanceNo }}" name="notes" rows="2" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">{{ $maintenance->notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end gap-2 mt-4">
                                                    <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Record</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="deleteMaintenanceModal-{{ $maintenance->maintenanceNo }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                    <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                                        <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                                            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deleteMaintenanceModal-{{ $maintenance->maintenanceNo }}">
                                                    <i data-lucide="x" class="size-4"></i>
                                                </button>
                                            </div>
                                            <div class="p-4 overflow-y-auto">
                                                <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the maintenance record for house <span id="deleteClientName" class="font-semibold text-red-500">{{ $maintenance->houseNo }}</span> with amount <span class="font-semibold text-red-500">{{ number_format($maintenance->amount, 2) }}</span>?</p>
                                                <div class="flex justify-end gap-2 mt-4">
                                                    <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deleteMaintenanceModal-{{ $maintenance->maintenanceNo }}">Cancel</button>
                                                    <form id="deleteMaintenanceForm-{{ $maintenance->maintenanceNo }}" method="POST" action="{{ route('maintenance/delete/{maintenanceNo}', ['maintenanceNo' => $maintenance->maintenanceNo]) }}">
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
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">No maintenance records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-x-5 mt-5">
            <div class="col-span-12 lg:col-span-6 card">
                <div class="card-body">
                    <h6 class="mb-4 text-15">Add New Maintenance Record</h6>
                </div>
            </div>
        </div>
    </div>
    </div>

<div id="addMaintenanceModal" modal-center=""
    class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-beSTEen p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="addMaintenanceLabel">Add Water Payment</h5>
            <button data-modal-close="addEmployeeModal" id="addEmployee"
                class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                    class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            
        <form id="addMaintenanceForm" action="{{ route('maintenance/add') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="addHouseNo" class="inline-block mb-2 text-base font-medium">Select House</label>
                    <select name="houseNo" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700" id="addHouseNo">
                        @foreach ($houses as $house)
                            <option value="{{ $house->houseNo }}">{{ $house->houseNo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="addDate" class="inline-block mb-2 text-base font-medium">Date</label>
                    <input type="date" id="addDate" name="maintenanceDate" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700">
                </div>
                <div>
                    <label for="addAmount" class="inline-block mb-2 text-base font-medium">Amount ($)</label>
                    <input type="number" id="addAmount" name="amount" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700" placeholder="e.g. 150.00">
                </div>
                <div class="col-span-2">
                    <label for="addDescription" class="inline-block mb-2 text-base font-medium">Description</label>
                    <textarea id="addDescription" rows="2" name="maintenanceDescription" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700" placeholder="e.g. Repaired leaking pipe"></textarea>
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

@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ URL::to('assets/js/pages/maintenance.js') }}"></script>

@endsection