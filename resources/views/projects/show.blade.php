<x-app-layout>

    <header class="flex justify-between items-end max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex font-semibold text-gray-400 leading-tight">
            <p>
                <a href="{{ route('projects.index') }}">{{ __('My Projects') }}</a> / {{ $project->title }}
            </p>
        </div>
    </header>

    <main>
        <div class="flex justify-between max-w-7xl mx-auto px-4">
            <div class="w-3/4 px-4">
                <div class="mb-8">
                    <h2 class="mb-4 text-lg">Tasks</h2>
                    @foreach($project->tasks as $task)
                        <div class="w-full p-4 mb-4 bg-white shadow rounded">{{ $task->body }}</div>
                    @endforeach
                </div>

                <div>
                    <h2 class="mb-4 text-lg">Notes</h2>
                    <div class="w-full h-40 p-4 mb-4 bg-white shadow rounded">Bla bla bla</div>
                </div>
            </div>

            <x-card class="w-1/4">
                <x-slot name="title">
                    {{ $project->title }}
                </x-slot>
                <x-slot name="description">
                    {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                </x-slot>
            </x-card>
        </div>
    </main>


</x-app-layout>
