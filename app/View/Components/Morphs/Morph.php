<?php

namespace App\View\Components\Morphs;

use App\Models\CactusEntity;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Morph extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public CactusEntity $model,
        public string $morph,
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (view()->exists('backend.morphs.' . $this->morph)) {
            return view('backend.morphs.' . $this->morph);
        }
        return '';
    }
}
