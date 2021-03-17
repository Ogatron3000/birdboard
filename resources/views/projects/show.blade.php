<x-app-layout>

    <header class="flex justify-between items-end max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex font-semibold text-gray-400 leading-tight">
            <p>
                <a href="{{ route('projects.index') }}">{{ __('My Projects') }}</a> / {{ $project->title }}
            </p>
        </div>
        <x-button>
            <a href="{{ $project->path() . '/edit' }}">Edit Project</a>
        </x-button>
    </header>

    <main>
        <div class="flex justify-between max-w-7xl mx-auto px-4">
            <div class="w-3/4 px-4">
                <div class="mb-8">
                    <h2 class="mb-4 text-lg">Tasks</h2>
                    @foreach($project->tasks as $task)
                        <div class="w-full p-4 mb-4 bg-white shadow rounded">
                            <form action="{{ $project->path() . '/tasks/' . $task->id }}" method="POST">
                                @method('PATCH')
                                @csrf

                                <div class="flex items-center">
                                    <input type="text" name="body" value="{{ $task->body }}" class="w-full border-0 {{ $task->completed ? 'text-gray-400 line-through' : '' }}" >
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }} class="mx-3" >
                                </div>
                            </form>

                        </div>
                    @endforeach
                    <div class="w-full p-4 mb-4 bg-white shadow rounded">
                        <form action="{{ $project->path() . '/tasks' }}" method="POST">
                            @csrf
                            <input class="w-full border-0" type="text" name="body" placeholder="Add new task...">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 text-lg">Notes</h2>
                    <form action="{{ $project->path() }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <textarea name="notes" class="w-full h-40 p-4 mb-4 bg-white shadow rounded border-0">{{ $project->notes }}</textarea>

                        <div class="flex items-center justify-end">
                            <x-button>
                                {{ __('Save Notes') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-1/4">
                <x-card>
                    <x-slot name="title">
                        {{ $project->title }}
                    </x-slot>
                    <x-slot name="description">
                        {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                    </x-slot>
                </x-card>

                <div class="p-4">
                    <div class="w-full p-6 text-xs bg-white shadow rounded">
                        <ul>
                            @foreach($project->activity as $activity)

                                <li class="flex justify-between {{ $loop->last ? '' : "mb-1"}}">
                                    @include("projects.activity.{$activity->description}")
                                    <span class="text-gray-400 text-right">
                                        {{ $activity->created_at->diffForHumans(null, true) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </main>


</x-app-layout>
