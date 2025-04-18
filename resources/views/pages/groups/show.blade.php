<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">Groupe {{ $group->id }} - Average : {{ $group->group_average }}</span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Étudiants</h3>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="30">
                            <div class="scrollable-x-auto">
                                <table class="table table-border" data-datatable-table="true">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[135px]">Nom</th>
                                        <th class="min-w-[135px]">Prénom</th>
                                        <th class="min-w-[135px]">Moyenne</th>
                                        <th class="min-w-[135px]">Date de naissance</th>
                                        <th class="max-w-[50px]"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($group->users as $user)
                                        <tr>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ number_format($user->average, 2) }}</td>
                                            <td>
                                                {{ $user->birthdate ? $user->birthdate->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="cursor-pointer pointer">
                                                <i class="ki-filled ki-trash"></i>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500">Aucun étudiant dans ce groupe.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
                                <div class="flex items-center gap-2 order-2 md:order-1">
                                    Show
                                    <select class="select select-sm w-16" data-datatable-size="true" name="perpage"></select>
                                    per page
                                </div>
                                <div class="flex items-center gap-4 order-1 md:order-2">
                                    <span data-datatable-info="true"></span>
                                    <div class="pagination" data-datatable-pagination="true"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
