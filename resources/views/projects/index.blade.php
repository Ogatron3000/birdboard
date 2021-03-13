<x-app-layout>

    <header class="flex justify-between items-end max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Projects') }}
        </h2>
        <x-button>
            <a href="{{ route('projects.create') }}">New Project</a>
        </x-button>
    </header>

    <main>
        <div class="flex flex-wrap max-w-7xl mx-auto px-4">
            @forelse($projects as $project)
                <x-card>
                    <x-slot name="title">
                        <a href="{{ $project->path() }}">{{ $project->title }}</a>
                    </x-slot>
                    <x-slot name="description">
                        {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                    </x-slot>
                </x-card>
            @empty
                <p>No projects yet.</p>
            @endforelse
        </div>
    </main>

</x-app-layout>
