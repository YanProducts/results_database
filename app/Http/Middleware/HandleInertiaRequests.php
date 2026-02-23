<?php

// Inertiaと関連づけるミドルウェア

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */

    // flashsessionなどをInertiaの引数に追加する
    public function share(Request $request): array
    {

        return [
            // inertiaの引数
            ...parent::share($request),
        ];
    }
}
