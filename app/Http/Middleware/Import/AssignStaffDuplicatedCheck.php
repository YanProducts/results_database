<?php

namespace App\Http\Middleware\Import;

use App\Exceptions\BusinessException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\DistributionAssignImport;

class AssignStaffDuplicatedCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!DistributionAssignImport::where("created_by",Auth::user()->id)->exists){
            throw new BusinessException("不正なルート\nもしくは時間切れです");
        }

        return $next($request);
    }
}
