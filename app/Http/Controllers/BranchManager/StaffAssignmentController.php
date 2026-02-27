<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

// スタッフへの案件割り当て系統を行う
class StaffAssignmentController extends Controller
{

    //案件割り当て画面へ
    public function assing_staff(){
        return Inertia::render("BranchManager/ProjectAssignment/AssignProjectToStaff.jsx",[
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て"
        ]);
    }
    //案件割り当て投稿
    public function assing_staff_post(){

    }
}
