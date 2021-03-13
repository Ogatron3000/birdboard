<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded text-sm text-white tracking-widest hover:bg-green-400 active:bg-green-400 focus:outline-none focus:border-green-500 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
