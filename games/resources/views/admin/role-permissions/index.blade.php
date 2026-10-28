<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permissies koppelen aan rollen</h2>
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

            @forelse ($roles as $role)
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">{{ $role->name }}</h3>
                    <form action="{{ route('admin.role-permissions.update', $role) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-2 text-gray-700">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        @checked($role->permissions->contains('id', $permission->id))
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if ($permissions->isEmpty())
                            <p class="text-gray-600">Maak eerst permissies aan via Permissies beheren.</p>
                        @endif
                        <button class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Koppelingen opslaan</button>
                    </form>
                </section>
            @empty
                <section class="rounded-lg bg-white p-6 shadow-sm text-gray-600">Maak eerst een rol aan via Rollen beheren.</section>
            @endforelse
        </div>
    </div>
</x-app-layout>
