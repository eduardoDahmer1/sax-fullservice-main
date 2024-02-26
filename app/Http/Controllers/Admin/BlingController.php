<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Services\Bling;
use Illuminate\Http\Request;

class BlingController extends Controller
{
    private Bling $bling;

    public function __construct()
    {
        $gs = Generalsetting::first();
        $this->bling = new Bling($gs->bling_access_token, $gs->bling_refresh_token);
    }

    public function authenticate()
    {
        return $this->bling->authorize();
    }

    public function setTokens(Request $request)
    {
        if (!$this->bling->isValidState($request->get('state'))) {
            abort(403);
        }

        $this->bling->generateTokens($request->get('code'));

        $gs = Generalsetting::first();
        $gs->bling_access_token = $this->bling->access_token;
        $gs->bling_refresh_token = $this->bling->refresh_token;
        $gs->bling_refresh_token_created_at = now();
        $gs->save();

        return redirect()->route('admin.dashboard');
    }
}
