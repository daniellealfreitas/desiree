<x-layouts.app :title="__('Usuarios')">
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Lista de Usuários</h1>
    <table class="min-w-full bg-white dark:bg-zinc-800 border border-gray-200 rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Nome</th>
                <th class="py-2 px-4 border-b">Username</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Papel</th>
                <th class="py-2 px-4 border-b">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->username }}</td>
                    <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b">{{ ucfirst($user->role) }}</td>
                    <td class="py-2 px-4 border-b">
                        {{-- <a href="{{ route('/'. $user->username) }}" class="text-blue-600 hover:underline">Ver Perfil</a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
</x-layouts.app>
