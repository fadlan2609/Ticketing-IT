@props([
    'label' => null,
    'name' => null,
    'options' => [], // ['value' => 'label']
    'selected' => null,
    'placeholder' => '-- Pilih --',
    'required' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-[13px] font-medium text-[#18181B] mb-1.5">
            {{ $label }}
            @if($required)<span class="text-[#DC2626] ml-0.5">*</span>@endif
        </label>
    @endif

    <select name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->class([
                'w-full px-3.5 py-2.5 text-[14px] bg-white border rounded-xl 
                 transition-all duration-150 outline-none appearance-none cursor-pointer',
                'border-[#E4E4E7] focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF]' 
                    => !$errors->has($name),
                'border-[#DC2626]' => $errors->has($name),
            ]) }}>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-[12px] text-[#DC2626] mt-1.5">{{ $message }}</p>
    @enderror
</div>