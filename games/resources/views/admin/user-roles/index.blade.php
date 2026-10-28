<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rollen koppelen aan gebruikers</h2>
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

            <section class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Gebruikers en hun rollen</h3>
                    <div class="space-y-5">
                        @forelse ($users as $user)
                            <form action="{{ route('admin.user-roles.update', $user) }}" method="POST" class="space-y-3 border-b border-gray-200 pb-5">
                                @csrf
                                @method('PUT')
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }} · ID {{ $user->id }}</p>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($roles as $role)
                                        <label class="flex items-center gap-2 text-gray-700">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                @checked($user->roles->contains('id', $role->id))
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                            <span>{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @if ($roles->isEmpty())
                                    <p class="text-gray-600">Maak eerst rollen aan via Rollen beheren.</p>
                                @endif
                                <button class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Rollen opslaan</button>
                            </form>
                        @empty
                            <p class="text-gray-600">Er zijn nog geen gebruikers.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
