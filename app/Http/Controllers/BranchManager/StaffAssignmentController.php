<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

// スタッフへの案件割り当て系統を行う
class StaffAssignmentController extends Controller
{

    //案件割り当て画面へ
    public function assign_staff(){
        return Inertia::render("BranchManager/ProjectAssignment/AssignProjectToStaff",[
            "type"=>"スタッフ割り当て"
        ]);
    }
    //案件割り当て投稿
    public function assign_staff_post(){

    }
}
