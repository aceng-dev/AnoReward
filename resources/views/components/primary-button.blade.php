<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#780000] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#5f0000] focus:bg-[#5f0000] active:bg-[#4a0000] focus:outline-none focus:ring-2 focus:ring-[#780000]/50 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
