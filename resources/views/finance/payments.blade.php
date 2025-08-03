@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Payments</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Finance</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Payments
                    </li>
                </ul>
            </div>

            <form method="GET" action="{{ route('payments') }}" class="mb-4">
            <div class="flex gap-4">
                <input type="text" name="houseNo" placeholder="House No" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                <input type="text" name="paymentType" placeholder="Payment Type" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                <input type="date" name="startDate" placeholder="Start Date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                <input type="date" name="endDate" placeholder="End Date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                <button type="submit" class="btn bg-custom-500 text-white">Filter</button>
            </div>
            </form>
            <div class="card" id="employeeTable">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-4">
                        <h6 class="text-15 grow">Payments<b class="total-Employs"></b></h6>
                        <div class="shrink-0">
                            <a href="#!" id="printButton" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                                <i data-lucide="printer" class="inline-block size-4"></i>
                                <span class="align-middle">Print</span>
                            </a>
                        </div>
                        <div class="shrink-0">
                            <a href="#!" data-modal-target="addPaymentModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 add-employee">
                                <i data-lucide="plus" class="inline-block size-4"></i> 
                                <span class="align-middle">Add Payment</span>
                            </a>
                        </div>
                    </div>

                    <div class="-mx-5 overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead class="bg-gray-50">
                                <tr class="bg-slate-100 dark:bg-zink-600">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">House No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Recorded</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Paid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-100 bg-slate-100 dark:bg-zink-600">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->paymentID }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->houseNo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->paymentType }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->timeRecorded }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->timePaid }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->paymentMethod }}</td>
                                        <td  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">   
                                            <div class="flex gap-3">
                                                <a href="#" data-modal-target="updateEmployeeModal" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 edit-item-btn bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500">
                                                    <i data-lucide="pencil" class="size-4"></i>
                                                </a>
                                                <a href="#" data-modal-target="deleteModal" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 remove-item-btn bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500">
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>        
            </div>
        </div>
    </div>

    <div id="addPaymentModal" modal-center=""
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-beSTEen p-4 border-b dark:border-zink-500">
                <h5 class="text-16" id="addEmployeeLabel">Add Payment</h5>
                <button data-modal-close="addEmployeeModal" id="addEmployee"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x"
                        class="size-5"></i></button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form class="create-form" id="create-form" method="POST" action="{{route('addPayment')}}" enctype="multipart/form-data">
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
                            <select
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                data-choices="" data-choices-search-false="" name="houseNo" id="houseNo">
                                @foreach ($houses as $house)
                                <option value="{{$house->houseNo}}">{{$house->houseNo}}</option>
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
                            <select
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                data-choices="" data-choices-search-false="" name="paymentType" id="paymentType">
                                @foreach ($paymentTypes as $paymentTypes)
                                <option value="{{$paymentTypes->paymentType}}">{{$paymentTypes->paymentType}}</option>
                                @endforeach
                                <option value="AddPaymentType">Add Payment Type</option>
                            </select>
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
                        <button type="reset" id="close-modal" data-modal-close="addPaymentModal"
                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                        <button type="submit" id="addNew"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 ">Add
                            Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end add Payment-->

    <!-- Add paymentType modal -->
<div id="addPaymentTypeModal" class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-md dark:bg-zink-600">
        <div class="flex justify-between items-center">
            <h5 class="text-lg font-medium">Add New PaymentType</h5>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form id="addPaymentTypeForm">
            <div class="mt-4">
                <label for="newPaymentType" class="block text-sm">Payment Type</label>
                <input type="text" id="paymentType" name="paymentType" class="form-input mt-1 block w-full">
            </div>
            <div class="mt-4">
                <label for="price" class="block text-sm">Price</label>
                <input type="text" id="price" name="price" class="form-input mt-1 block w-full">
            </div>
            <div class="flex justify-center gap-2 mt-6">
                <button type="reset" id="cancelButton" class="bg-white text-slate-500 btn hover:text-slate-500 hover:bg-slate-100 focus:text-slate-500 focus:bg-slate-100 active:text-slate-500 active:bg-slate-100 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10">Cancel</button>
                <button type="submit" id="submitAddPaymentType" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 ">
                    Add PaymentType
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add PaymentType -->
<script>
    document.getElementById('typeSelect').addEventListener('change', function () {
        if (this.value === 'AddPaymentType') {
            document.querySelectorAll('.fixed:not(#addPaymentTypeModal)').forEach(function(modal) {
                modal.classList.add('hidden');
            });
            document.getElementById('addPaymentTypeModal').classList.remove('hidden');
        }
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('addPaymentTypeModal').classList.add('hidden');
    });

    document.getElementById('cancelButton').addEventListener('click', function () {
        document.getElementById('addPaymentTypeModal').classList.add('hidden');
    });

    document.getElementById('addPaymentTypeForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const newPaymentType = document.getElementById('newPaymentType').value;

        fetch('{{ route('addPaymentType') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ PaymentType: newPaymentType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                // Handle errors
                alert('Failed to add paymentType');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            window.location.href = '{{ route("payments.downloadPdf") }}';
        });
    </script>
    @section('script')
    <script src="{{ URL::to('assets/js/pages/apps-hr-employee.init.js') }}"></script>
    @endsection
    @endsection
