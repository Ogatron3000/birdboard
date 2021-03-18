<div {{ $attributes->merge(['class' => "p-4 pt-0"]) }}>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="flex flex-col h-52 p-6 bg-white border-b border-gray-200">
            <h3 class="py-2 mb-4 -ml-6 border-l-4 border-green-400 pl-5 text-lg">
                {{ $title }}
            </h3>
            <p class="break-words flex-1">
                {{ $description }}
            </p>
            <div class="text-right text-xs">
                {{ $deleteForm }}
            </div>
        </div>
    </div>
</div>
