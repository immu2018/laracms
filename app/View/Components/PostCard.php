<?php
// Deprecated: Use Blade partial instead of component.

namespace App\View\Components;

use Illuminate\View\Component;

class PostCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // This component is deprecated. Use resources/views/partials/post-card.blade.php instead.
    public function render()
    {
        return '';
    }
}
