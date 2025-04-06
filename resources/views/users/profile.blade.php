<x-layouts.app title="User Profile">

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('storage/' . ($user->profile_photo_path ?? 'default.png')) }}" alt="Avatar" class="w-24 h-24 rounded-full">
            <div>
                <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-gray-500 text-sm">{{ $user->address }}</p>
            </div>
        </div>
        <div class="mt-4 flex space-x-4">
            <div>
                <span class="font-bold">{{ $user->posts->count() }}</span> Posts
            </div>
            <div>
                <span class="font-bold">{{ $user->followers->count() }}</span> Seguidores
            </div>
            <div>
                <span class="font-bold">{{ $user->following->count() }}</span> Seguindo
            </div>
        </div>
        <div class="mt-4">
            <!-- Botão para seguir/deixar de seguir -->
            <form action="{{ route('follows.toggle', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                    @if(auth()->user()->following->contains($user->id))
                        Deixar de Seguir
                    @else
                        Seguir
                    @endif
                </button>
            </form>
        </div>
    </div>

</x-layouts.app>