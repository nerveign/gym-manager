@props(['location' => '/', 'size' => 'size-10', 'text' => '', 'src' => 'logo.svg', 'color' => 'black', "style" => ""])


<a href="{{ route($location) }}" class="flex items-center gap-2 px-6 py-3 hover:bg-gray-100 {{  $style }}">
    <img src="{{ asset('icons/' . $src) }}" alt="Logo">
    <span class="text-sm {{ $color }}" >{{ $text }}</span>
</a>