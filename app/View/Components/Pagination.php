<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $data;

    /**
     * Create a new component instance.
     *
     * @param mixed $data
     */    
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
       
        return view('components.pagination', [
            'data' => $this->data,
        ]);
    }
}
