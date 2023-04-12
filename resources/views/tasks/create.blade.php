<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('tasks.store') }}">
            @include('tasks.form')
            <div class="mt-4 space-x-2">
                <x-primary-button>{{ __('Create') }}</x-primary-button>
                <a href="{{ route('tasks.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
    <x-slot name="scripts">
        <!-- Script -->
        <script type="text/javascript">
            // CSRF Token
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(document).ready(function () {
                $("#users").select2({
                    ajax: {
                        url: "{{route('ajax.users', ['type' => 1])}}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term // search term
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });

                $("#admins").select2({
                    ajax: {
                        url: "{{route('ajax.users', ['type'=>2])}}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term // search term
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
