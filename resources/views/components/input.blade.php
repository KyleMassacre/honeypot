@props(['name', 'cssClass'])

<input type="text" name="{{ $name }}" class="{{ $cssClass }}" autocomplete="new-{{ $name }}">
