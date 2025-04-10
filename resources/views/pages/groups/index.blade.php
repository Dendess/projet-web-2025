<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Groupes') }}
            </span>
        </h1>
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">
                           Add Group
                        </h3>
                    </div>
                    <div class="card-body flex flex-col gap-5">

                    </div>
                </div>
            </div>
            <div class="card-body flex flex-col gap-5">
                <button wire:click="AddGroup"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Add Group
                </button>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="card card-grid h-full min-w-full">
                <div class="card-header">
                    <h3 class="card-title">
                        Block 2
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                </div>
            </div>
        </div>
    </x-slot>
    <!-- Check if there are any groups -->
    @if($groups->isEmpty())
        <p>No groups found.</p>
    @else
        <table border="1">
            <thead>
            <tr>
                <th>Group Name</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td> <!-- Group name -->
                    <td>{{ $group->description }}</td> <!-- Group description -->
                    <td>{{ $group->created_at->format('Y-m-d') }}</td> <!-- Group created at -->
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</x-app-layout>
