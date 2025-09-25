<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public string $message;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type = 'success', string $message = '')
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function icon(): string
    {
        return match ($this->type) {
            'success' => 'lucide-circle-check',
            'error'   => 'lucide-circle-x',
            'warning' => 'lucide-circle-alert',
            'info'    => 'lucide-info',
        };
    }

    public function classes(): string
    {
        return match ($this->type) {
            'success' => 'alert-success',
            'error'   => 'alert-error',
            'warning' => 'alert-warning',
            'info'    => 'alert-info',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
