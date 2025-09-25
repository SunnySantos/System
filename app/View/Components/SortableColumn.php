<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortableColumn extends Component
{
    public string $column;
    public string $label;

    /**
     * Create a new component instance.
     */
    public function __construct(string $column, string $label)
    {
        $this->column = $column;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sortable-column');
    }

    public function url(): string
    {
        return request()->fullUrlWithQuery([
            'sort' => $this->column,
            'direction' => request()->get('direction') === 'asc' ? 'desc' : 'asc'
        ]);
    }

    public function icon(): string
    {
        if (request()->get('sort') === $this->column) {
            return request()->get('direction') === 'asc' ? 'lucide-arrow-up-narrow-wide' : 'lucide-arrow-down-wide-narrow';
        }

        return 'lucide-arrow-down-up';
    }
}
