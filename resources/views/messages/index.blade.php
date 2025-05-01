<x-layouts.app :title="__('Mensagens')">
<div class="container">
    <h1>Messages</h1>

    <!-- Dropdown to select a user -->
    <form method="GET" action="{{ route('messages.index') }}">
        <div class="form-group">
            <label for="receiver_id">Select User:</label>
            <select name="receiver_id" id="receiver_id" class="form-control bg-zinc-800 border border-gray-300" onchange="this.form.submit()">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('receiver_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Message history -->
    <div class="mt-4">
        <h2>Message History</h2>
        <ul class="list-group">
            @forelse($messages as $message)
                <li class="list-group-item">
                    <strong>{{ $message->sender->name }}:</strong> {{ $message->body }}
                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item">No messages found.</li>
            @endforelse
        </ul>
    </div>

    <!-- Form to send a new message -->
    <div class="mt-4">
        <h2>Send a Message</h2>
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            <div class="form-group">
                <label for="receiver_id">To:</label>
                <select name="receiver_id" id="receiver_id" class="form-control bg-zinc-800 border border-gray-300">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control bg-zinc-800 border border-gray-300" placeholder="Escreva aqui">
                <label for="body">Message:</label>
                <textarea class="border border-gray-300" name="body" id="body" class="form-control" rows="3" palceholder="Escrava aqui"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>
</x-layouts.app>