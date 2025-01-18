<div class="relative" x-data="{ showHelp: false }" @click.away="showHelp = false">
    <button @click.prevent.stop="showHelp = !showHelp" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
        <i class="fas fa-question-circle mr-2"></i>
        Help & Guide
    </button>

    <!-- Help Dropdown -->
    <div x-show="showHelp"
         x-cloak
         @click.stop
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 w-72 rounded-md shadow-lg origin-top-right right-0">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Documentation</h3>
                <p class="text-sm text-gray-500">Click any topic for detailed help</p>
            </div>
            
            <div class="px-4 py-2" @click.stop>
                <div class="space-y-3">
                    @include('documentation.guides.compose-guide')
                    @include('documentation.guides.folder-guide')
                    @include('documentation.guides.search-guide')
                </div>
            </div>
        </div>
    </div>
</div> 