<?php

namespace Larapress\Honeypot\View;

use Illuminate\View\Component;
use Larapress\Honeypot\Facades\Honeypot;

class HoneypotComponent extends Component
{
    public function __construct(public string|null $cssClass = null, public array|null $fields = null)
    {
        if(! $this->cssClass) {
            $this->cssClass = 'my-awesome-inputs';
        }
        if(! $this->fields) {
            $this->fields = Honeypot::fields();
        }
    }

    public function render()
    {
        return view('honeypot::components.honeypot');
    }
}
