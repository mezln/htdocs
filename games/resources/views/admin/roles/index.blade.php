<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rollen beheren</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-green-800">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Rol toevoegen</h3>
                <form action="{{ route('admin.roles.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
                    @csrf
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700">Naam</span>
                        <input name="name" value="{{ old('name') }}" required maxlength="255" class="mt-1 block rounded-md border-gray-300 shadow-sm">
                    </label>
                    <span class="text-sm text-gray-600">Guard: web</span>
                    <button class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Toevoegen</button>
                </form>
            </section>

            <section class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Bestaande rollen</h3>
                    <div class="space-y-4">
                        @forelse ($roles as $role)
                            <div class="flex flex-wrap items-center gap-3 border-b border-gray-200 pb-4">
                                <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="flex flex-wrap items-end gap-3">
                                    @csrf
                                    @method('PUT')
                                    <label class="block">
                                        <span class="text-sm font-medium text-gray-700">Naam</span>
                                        <input name="name" value="{{ $role->name }}" required maxlength="255" class="mt-1 block rounded-md border-gray-300 shadow-sm">
                                    </label>
                                    <span class="text-sm text-gray-600">Guard: {{ $role->guard_name }} · {{ $role->permissions_count }} permissies</span>
                                    <button class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Opslaan</button>
                                </form>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Deze rol en de bijbehorende koppelingen verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700">Verwijderen</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-600">Er zijn nog geen rollen.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
