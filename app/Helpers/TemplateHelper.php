<?php
// app/Helpers/TemplateHelper.php

namespace App\Helpers;

class TemplateHelper
{
    /**
     * Resolve the most specific Blade template from a list of candidates.
     *
     * @param array $candidates List of Blade view names (ordered most specific to least).
     * @param array $data Data to pass to the view.
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public static function resolve(array $candidates, array $data = [])
    {
        foreach ($candidates as $view) {
            if (view()->exists($view)) {
                return view($view, $data);
            }
        }
        abort(404);
    }
}
