@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#780000] focus:ring-[#780000]/50 rounded-md shadow-sm']) }}>
