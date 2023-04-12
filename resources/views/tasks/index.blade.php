<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-7xl">
        <div class="py-8">
            <div class="flex flex-row mb-1 sm:mb-0 justify-between w-full">
                <h2 class="text-2xl leading-tight">
                    Tasks
                </h2>
                <div class="text-end">
                    <div>
                        <a href="{{ route('tasks.create') }}"
                           class="py-2 px-4 bg-gray-800 hover:bg-indigo-700 focus:ring-indigo-500 focus:ring-offset-indigo-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg">
                            Add New
                        </a>
                    </div>
                </div>
            </div>

            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4">
                <div class="inline-block min-w-full shadow rounded-lg">
                    <table class="min-w-full leading-normal" id="kt_datatable">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                @lang('Title')
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                @lang('Description')
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                User
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Created at
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <x-slot name="scripts">
            <script type="text/javascript">
                $(document).ready(function () {
                    var table = $('#kt_datatable');
                    $.fn.dataTable.ext.classes.sPageButton = 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150';

                    // begin first table
                    table.DataTable({
                        responsive: true,
                        searchDelay: 500,
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('ajax.tasks') }}",
                        lengthMenu: [5, 10, 25, 50, 100],
                        pageLength: 10,
                        language: {
                            'lengthMenu': 'Display _MENU_',
                        },
                        // Order settings
                        order: [[0, 'desc']],
                        columnDefs: [
                            {
                                targets: 0,
                                data: 'title',
                                className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                                render: function (data, type, full, meta) {
                                    return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                                }
                            },
                            {
                                targets: 1,
                                data: 'description',
                                className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                                render: function (data, type, full, meta) {
                                    return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                                }
                            },

                            {
                                targets: 2,
                                data: 'user_id',
                                className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                                render: function (data, type, full, meta) {
                                    return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                                }
                            },
                            {
                                targets: 3,
                                data: 'created_at',
                                className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                                render: function (data, type, full, meta) {
                                    return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                                }
                            },
                            {
                                targets: 4,
                                data: 'actions',
                                orderable: false,
                                width: '125px',
                                className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                                render: function (data, type, full, meta) {
                                    return `<x-dropdown>
                                        <x-slot name="trigger">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link href="#">
                                                {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <form method="POST"
                                          action="#">
                                                @csrf
                                    @method('delete')
                                    <x-dropdown-link href="#"
                                                     onclick="event.preventDefault(); this.closest('form').submit();">
{{ __('Delete') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>`;
                                },
                            }
                        ],
                    });

                });
            </script>
        </x-slot>
</x-app-layout>

