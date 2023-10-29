@foreach(\Larapress\Honeypot\Facades\Honeypot::fields() as $fieldName)
<x-honeypot::input :name="$fieldName" :css-class="$cssClass" />
@endforeach

<style>
    .{{ $cssClass }} {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        height: 0;
        width: 0;
        z-index: -1;
    }
</style>
