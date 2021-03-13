<x-app-layout>

    <div class="flex sm:justify-center pt-6">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div>
                    <x-label for="title" :value="__('Title')" />
                    <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                </div>

                <div class="mt-4">
                    <x-label for="description" :value="__('Description')" />
                    <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autofocus />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ml-3">
                        {{ __('Submit') }}
                    </x-button>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>
