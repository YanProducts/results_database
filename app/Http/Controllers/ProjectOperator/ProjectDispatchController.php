<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectDispatchController extends Controller
{
    // 営業所(外注含む)へ振る案件を選択する画面を表示
    public function dispatch_project(){
      return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            "what"=>"案件操作",
            "type"=>"割り当て"
        ]);
    }

    // 営業所(外注含む)へ振る案件を決定
    public function dispatch_project_post(){

        return Inertia::render("ProjectOperator/SendProjectsToBranch");
    }
}
