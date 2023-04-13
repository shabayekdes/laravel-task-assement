<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistics') }}
        </h2>
    </x-slot>
    <div class="container mx-auto px-4 sm:px-8 max-w-7xl">
        <div class="py-8">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4">
                <div class="inline-block min-w-full shadow rounded-lg">
                    <table class="min-w-full leading-normal" id="kt_datatable">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Name
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Email
                            </th>
                            <th scope="col"
                                class="px-5 py-3 bg-white  border-b border-gray-200 text-gray-800  text-left text-sm uppercase font-normal">
                                Total Tasks
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script type="text/javascript">
            $(document).ready(function () {
                $.fn.dataTable.ext.classes.sPageButton = 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150';

                // begin first table
                let table = $('#kt_datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('ajax.users.list') }}",
                    ordering: false,
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [
                        {
                            targets: 0,
                            data: 'user_name',
                            className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                            render: function (data, type, full, meta) {
                                return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                            }
                        },
                        {
                            targets: 1,
                            data: 'user_email',
                            className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                            render: function (data, type, full, meta) {
                                return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                            }
                        },
                        {
                            targets: 2,
                            data: 'tasks_count',
                            className: 'px-5 py-5 border-b border-gray-200 bg-white text-sm',
                            render: function (data, type, full, meta) {
                                return `<p class="text-gray-900 whitespace-no-wrap">${data}</p>`
                            }
                        }
                    ],
                });

                // setInterval(function () {
                //     table.ajax.reload(null, false);
                // }, 3000);
            });
        </script>
    </x-slot>
</x-app-layout>
