@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'hint' => null,
    'icon' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-[13px] font-medium text-[#18181B] mb-1.5">
            {{ $label }}
            @if($required)<span class="text-[#DC2626] ml-0.5">*</span>@endif
        </label>
    @endif

    <div class="relative">
        @if($icon === 'search')
            <svg class="w-4 h-4 text-[#A1A1AA] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        @endif

        <input type="{{ $type }}"
               name="{{ $name }}"
               id="{{ $name }}"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
               @if($required) required @endif
               {{ $attributes->class([
                   'w-full px-3.5 py-2.5 text-[14px] bg-white border rounded-xl 
                    transition-all duration-150 outline-none',
                   'pl-10' => $icon === 'search',
                   'border-[#E4E4E7] focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF]' 
                       => !$errors->has($name),
                   'border-[#DC2626] focus:border-[#DC2626] focus:ring-2 focus:ring-[#FEF2F2]' 
                       => $errors->has($name),
                   'placeholder:text-[#A1A1AA]',
               ]) }}>
    </div>

    @if($hint && !$errors->has($name))
        <p class="text-[12px] text-[#71717A] mt-1.5">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-[12px] text-[#DC2626] mt-1.5">{{ $message }}</p>
    @enderror
</div>