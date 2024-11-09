<div class="p-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold mb-4">Laravel AI Translation Manager</h2>
        
        <!-- Locale Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Select Target Locale</label>
            <select wire:model="selectedLocale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($availableLocales as $locale)
                    <option value="{{ $locale }}">{{ strtoupper($locale) }}</option>
                @endforeach
            </select>
        </div>

        <!-- View File Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Select View File</label>
            <select wire:model="selectedView" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Select a view file...</option>
                @foreach($viewFiles as $file)
                    <option value="{{ $file }}">{{ $file }}</option>
                @endforeach
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-4">
            <button wire:click="translateView" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    wire:loading.attr="disabled">
                Translate View
            </button>
            
            <button wire:click="translateMissing"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                    wire:loading.attr="disabled">
                Translate Missing
            </button>
        </div>

        <!-- Loading State -->
        <div wire:loading class="mt-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2">Processing translations...</span>
        </div>

        <!-- Translation Files -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Translation Files</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($files as $file)
                    <div class="p-4 border rounded">
                        <h4 class="font-medium">{{ $file }}</h4>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>