<x-app-layout>

    <header class="flex justify-between items-end max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex font-semibold text-gray-400 leading-tight">
            <p>
                <a href="{{ route('projects.index') }}">{{ __('My Projects') }}</a> / {{ $project->title }}
            </p>
        </div>
        <div class="flex items-center">
            @foreach($project->members as $member)
                <img src="{{ $member->gravatar }}" alt="{{ $member->name }}'s avatar" class="mr-2 rounded-full">
            @endforeach
            <img src="{{ $project->user->gravatar }}" alt="{{ $project->user->name }}'s avatar" class="mr-2 rounded-full">

            <x-button class="ml-4">
                <a href="{{ $project->path() . '/edit' }}">Edit Project</a>
            </x-button>
        </div>
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
                        @error('body')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
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
                    <x-slot name="deleteForm">
                        <form method="POST" action="{{ $project->path() }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit">Delete</button>
                        </form>
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

                @can('manage', $project)
                    <div class="p-4">
                        <div class="w-full p-6 bg-white shadow rounded">
                            <h3 class="text-lg mb-4">Invite Users</h3>
                            <form method="POST" action="{{ $project->path() . '/invitations' }}">
                                @csrf
                                <input type="text" name="email" placeholder="bruce@wayne.com">
                                @error('email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <div class="flex items-center justify-end mt-4">
                                    <x-button>
                                        {{ __('Invite') }}
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan

            </div>

        </div>
    </main>


</x-app-layout>
