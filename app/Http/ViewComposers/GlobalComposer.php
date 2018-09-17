<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Managers\Navigation\NavigationManager;

class GlobalComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $access = (null !== Auth::id()) ? Auth::user()->accesslevel : 5;
        $view->with('navigation', NavigationManager::search($access)->extract());
    }

}