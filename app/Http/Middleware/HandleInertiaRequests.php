<?php

// Inertiaと関連づけるミドルウェア

namespace App\Http\Middleware;

use App\Support\Auth\UserRoleResolver;
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
    // 全ページ共通のpropsを渡すのもここ
    public function share(Request $request): array
    {
        // 全ページ共通
       $role_sets=UserRoleResolver::get_page_name_sets($request->route()->getName());

        return [
            "prefix"=>$role_sets["prefix"],
            "what"=>$role_sets["what"],
            // inertiaの引数
            ...parent::share($request),
        ];
    }
}
