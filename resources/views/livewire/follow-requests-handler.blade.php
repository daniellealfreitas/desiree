<div>
    @foreach($pendingRequests as $request)
        <div class="flex items-center justify-between p-4 border rounded-lg mb-2">
            <div class="flex items-center space-x-3">
                <img src="{{ !empty($request->sender->userPhotos->first()) ? Storage::url($request->sender->userPhotos->first()->photo_path) : asset('images/users/default.jpg') }}" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold">{{ $request->sender->name }}</p>
                    <p class="text-sm text-gray-500">@{{ $request->sender->username }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button wire:click="acceptRequest({{ $request->id }})" 
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Accept
                </button>
                <button wire:click="rejectRequest({{ $request->id }})" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Reject
                </button>
            </div>
        </div>
    @endforeach
</div>
