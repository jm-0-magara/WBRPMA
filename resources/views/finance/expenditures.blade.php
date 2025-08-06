@extends('layouts.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Initial data passed from the controller
        var initialChartLabels = @json($labels);
        var initialChartData = @json($data);
        var filterRoute = '{{ route("expenditures/filter") }}';
        var addExpenditureRoute = '{{ route("expenditures/add") }}';
        var updateExpenditureRouteBase = '{{ url("expenditures/update") }}'; // Base URL for update
        var deleteExpenditureRouteBase = '{{ url("expenditures/delete") }}'; // Base URL for delete
        var showExpenditureRouteBase = '{{ url("expenditures/show") }}'; // Base URL for showing a single expenditure
    </script>
<script src="{{ URL::to('assets/js/pages/expenditures.js') }}"></script>

<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">Expenditures</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">{{ Session::has('rentalName') ? Session::get('rentalName') : 'Select a property' }}</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Expenditures
                </li>
            </ul>
        </div>

        <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu-width group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu-width mt-[97px] px-4">
            <div class="container-fluid">

                <div class="card p-6 mb-6 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                    <h6 class="mb-4 text-15 font-semibold text-slate-800 dark:text-zink-100">Expenditure Overview</h6>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="expenditureTypeSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Type</label>
                            <select id="expenditureTypeSelect" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full">
                                <option value="all">All Types</option>
                                @foreach ($expenditureTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
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
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount Spent</p>
                            <h5 id="totalAmountSpent" class="text-3xl font-bold mt-1 text-slate-800 dark:text-zink-100">Ksh {{ number_format($totalAmountSpent, 2) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md">
                        <div id="expendituresChart" class="apex-charts"></div>
                    </div>

                    <div class="card p-4 bg-white dark:bg-zink-700 rounded-lg shadow-md mt-6">
                        <div class="overflow-x-auto">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-15 grow">Expenditure Records</h6>
                                <div class="shrink-0">
                                    <a href="#!" data-modal-target="addExpenditureModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                        <i data-lucide="plus" class="inline-block size-4"></i>
                                        <span class="align-middle">Add Expenditure</span>
                                    </a>
                                </div>
                            </div>
                            <table class="table-auto w-full text-left whitespace-nowrap text-slate-800 dark:text-zink-100" id="expendituresTable">
                                <thead>
                                    <tr class="bg-slate-100 dark:bg-zink-600">
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Expenditure ID</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Expenditure Type</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Amount (Ksh)</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Date Paid</th>
                                        <th class="px-4 py-3 font-semibold text-sm border-b border-slate-200 dark:border-zink-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="expendituresTableBody">
                                    @if(isset($expenditures) && $expenditures->count() > 0)
                                    @foreach ($expenditures as $expenditure)
                                    <tr class="border-b border-slate-200 last:border-b-0 dark:border-zink-500">
                                        <td class="px-4 py-2">{{ $expenditure->expenditureID }}</td>
                                        <td class="px-4 py-2">{{ $expenditure->expenditureType }}</td>
                                        <td class="px-4 py-2">{{ number_format($expenditure->amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($expenditure->timePaid)->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 first:pl-5 last:pr-5 ltr:text-right rtl:text-left">
                                            <div class="flex items-center gap-2 justify-end">
                                                <div class="relative group">
                                                    <button type="button" data-modal-target="editExpenditureModal-{{ $expenditure->expenditureID }}" class="edit-expenditure-btn flex items-center justify-center p-2 text-sky-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-sky-500 hover:text-white">
                                                        <i data-lucide="edit-2" class="size-4"></i>
                                                    </button>
                                                    <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                        Edit
                                                    </div>
                                                </div>
                                                <div class="relative group">
                                                    <button type="button" data-modal-target="deleteExpenditureModal-{{ $expenditure->expenditureID }}" class="delete-expenditure-btn flex items-center justify-center p-2 text-red-500 transition-all duration-200 ease-linear rounded-md shrink-0 hover:bg-red-500 hover:text-white">
                                                        <i data-lucide="trash-2" class="size-4"></i>
                                                    </button>
                                                    <div class="absolute invisible px-2 py-1 text-xs text-white -top-10 -left-10 bg-slate-700 rounded-md group-hover:visible dark:bg-zink-500">
                                                        Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Expenditure Modal -->
                                    <div id="editExpenditureModal-{{ $expenditure->expenditureID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600" data-modal-close-outside="editExpenditureModal-{{ $expenditure->expenditureID }}">
                                            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                                                <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Update Expenditure Record</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="editExpenditureModal-{{ $expenditure->expenditureID }}">
                                                    <i data-lucide="x" class="size-4"></i>
                                                </button>
                                            </div>
                                            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                                                <form id="updateExpenditureForm-{{ $expenditure->expenditureID }}" action="{{ route('expenditures/update', ['expenditureID' => $expenditure->expenditureID]) }}" method="POST">
                                                    @csrf
                                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                        <div>
                                                            <label for="updateExpenditureType-{{ $expenditure->expenditureID }}" class="inline-block mb-2 text-base font-medium">Expenditure Type</label>
                                                            <select name="expenditureType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="updateExpenditureType-{{ $expenditure->expenditureID }}" required>
                                                                @foreach ($expenditureTypes as $type)
                                                                <option value="{{ $type }}" @if($expenditure->expenditureType == $type) selected @endif>{{ $type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="updateAmount-{{ $expenditure->expenditureID }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                                                            <input type="number" step="0.01" name="amount" value="{{ $expenditure->amount }}" id="updateAmount-{{ $expenditure->expenditureID }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="e.g. 1500.00" required>
                                                        </div>
                                                        <div class="col-span-full">
                                                            <label for="updateTimePaid-{{ $expenditure->expenditureID }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Paid</label>
                                                            <input type="date" name="timePaid" id="updateTimePaid-{{ $expenditure->expenditureID }}" value="{{ \Carbon\Carbon::parse($expenditure->timePaid)->format('YYYY-MM-DD') }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <button type="submit" class="text-white btn bg-yellow-500 border-yellow-500 hover:text-white hover:bg-yellow-600">Update Expenditure</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Expenditure Modal -->
                                    <div id="deleteExpenditureModal-{{ $expenditure->expenditureID }}" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
                                        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
                                            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                                                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-zink-500">
                                                    <h5 class="text-lg font-medium text-gray-900 dark:text-zink-100">Confirm Deletion</h5>
                                                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-zink-200" data-modal-close="deleteExpenditureModal-{{ $expenditure->expenditureID }}">
                                                        <i data-lucide="x" class="size-4"></i>
                                                    </button>
                                                </div>
                                                <div class="p-4 overflow-y-auto">
                                                    <p class="text-gray-700 dark:text-zink-200">Are you sure you want to delete the expenditure record for <span class="font-semibold text-red-500">{{ $expenditure->expenditureType }}</span> with ID <span class="font-semibold text-red-500">{{ $expenditure->expenditureID }}</span>?</p>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <button type="button" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300" data-modal-close="deleteExpenditureModal-{{ $expenditure->expenditureID }}">Cancel</button>
                                                        <form id="deleteExpenditureForm-{{ $expenditure->expenditureID }}" method="POST" action="{{ route('expenditures/delete', ['expenditureID' => $expenditure->expenditureID]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="expenditureID" value="{{ $expenditure->expenditureID }}">
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
                                        <td colspan="5" class="text-center py-4">No expenditure records found.</td>
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

<!-- Add Expenditure Modal -->
<div id="addExpenditureModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="addExpenditureLabel">Add New Expenditure</h5>
            <button data-modal-close="addExpenditureModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            <form id="addExpenditureForm" method="POST" action="{{ route('expenditures/add') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                    <div class="xl:col-span-12">
                        <label for="addExpenditureID" class="inline-block mb-2 text-base font-medium">Expenditure ID</label>
                        <input type="text" id="addExpenditureID" name="expenditureID" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="Enter Expenditure ID" required>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addExpenditureType" class="inline-block mb-2 text-base font-medium">Expenditure Type</label>
                        <select name="expenditureType" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" id="addExpenditureType" required>
                            <option value="">Select Expenditure Type</option>
                            @foreach ($expenditureTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addAmount" class="inline-block mb-2 text-base font-medium">Amount</label>
                        <input type="number" step="0.01" id="addAmount" name="amount" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" placeholder="Amount Spent" required>
                    </div>
                    <div class="xl:col-span-12">
                        <label for="addTimePaid" class="inline-block mb-2 text-base font-medium">Date Paid</label>
                        <input type="date" id="addTimePaid" name="timePaid" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 dark:text-zink-100 w-full" required>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="reset" data-modal-close="addExpenditureModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                    <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Expenditure</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ URL::to('assets/js/pages/expenditures.js') }}"></script>
@endsection
