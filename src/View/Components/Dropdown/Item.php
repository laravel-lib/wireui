<?php

namespace WireUi\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use WireUi\View\Components\WireUiComponent;

class Item extends WireUiComponent
{
    protected array $props = [
        'icon'      => null,
        'label'     => null,
        'separator' => false,
    ];

    public function blade(): View
    {
        return view('wireui::components.dropdown.item');
    }
}
