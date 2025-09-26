<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BulkDeleteForm extends Component
{
    public string $singular;
    public string $plural;
    public string $route;

    /**
     * Create a new component instance.
     */
    public function __construct(string $singular, string $plural, string $route)
    {
        $this->singular = $singular;
        $this->plural = $plural;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bulk-delete-form');
    }
}
