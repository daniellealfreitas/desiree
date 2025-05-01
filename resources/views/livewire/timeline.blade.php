<div>
    <div class="space-y-8">
        @foreach ($rows as $rowIndex => $row)
            <div class="relative flex items-center space-x-8">
                @foreach ($row as $divIndex => $div)
                    <div class="relative bg-blue-500 text-white p-4 rounded shadow-md w-32 h-32 flex items-center justify-center">
                        {{ $div }}
                        @if ($divIndex < 3)
                            <div class="absolute top-1/2 right-0 transform translate-x-4 -translate-y-1/2 w-8 h-1 bg-gray-400"></div>
                        @endif
                    </div>
                @endforeach
                @if ($rowIndex < count($rows) - 1)
                    <div class="absolute bottom-0 right-0 transform translate-x-4 translate-y-8 w-8 h-8 border-b-4 border-r-4 border-gray-400 rounded-br-lg"></div>
                    <div class="absolute bottom-0 left-0 transform -translate-x-4 translate-y-8 w-full h-8 bg-gray-400"></div>
                    <div class="absolute bottom-0 left-0 transform -translate-x-4 translate-y-8 w-8 h-8 border-t-4 border-l-4 border-gray-400 rounded-tl-lg"></div>
                @endif
            </div>
        @endforeach
    </div>
    <button wire:click="addDiv" class="mt-8 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        Add Div
    </button>
</div>
