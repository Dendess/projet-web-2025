<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Groupes') }}
            </span>
        </h1>
    </x-slot>
    <tbody>

    </tbody>
    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Mes groupes</h3>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="5">
                            <div class="scrollable-x-auto">
                                <table class="table table-border" data-datatable-table="true">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[280px]">
                                            <span class="sort asc">
                                                 <span class="sort-label">groupe</span>
                                                 <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Année</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Etudiants</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                    @forelse($groups as $group)
                                        <tr>
                                            <td>
                                                <div class="flex flex-col gap-2">
                                                    <a class="leading-none font-medium text-sm text-gray-900 hover:text-primary"
                                                       href="{{ route('groups.show', $group->id) }}">
                                                        Groupe #{{ $group->id }}
                                                    </a>
                                                    <span class="text-2sm text-gray-700 font-normal leading-3">
                    Moyenne : {{ number_format($group->group_average, 2) }}
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ now()->year }}-{{ now()->addYear()->year }}
                                            </td>
                                            <td>
                                                {{ $group->users->count() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-gray-500">Aucun groupe trouvé.</td>
                                        </tr>
                                        @endforelse
                                    </tr>
                                    </thead>

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
        @can('create', \App\Models\Group::class)
            <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">
                        Ajouter des groupes
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <form method="POST" action="{{ route('generate.groups') }}">
                        @csrf

                        <x-forms.input name="nb" :label="__('Taille')" />

                        <x-forms.select name="nbp" :label="__('Promotion')" :options="$cohortsall->pluck('name'   , 'id')" />              <x-forms.primary-button>
                            {{ __('Valider') }}
                        </x-forms.primary-button>
                    </form>

                </div>
            </div>
        </div>
        @endcan
    </div>
    <!-- end: grid -->
</x-app-layout>
