<?php

namespace App\Http\Controllers\FieldStaffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//スタッフから報告書の確認や編集のコントローラー
class ReportManagementController extends Controller
{

    // 過去提出した報告書の確認
    public function overview_reports(){
        dd(1);
    }

    // 指定日の報告書の確認や編集に向かう画面
    public function show_detail_report($data){

    }

    // 編集の画面
    public function edit_report($data){

    }

    // 実際の編集の投稿
    public function edit_report_post(){

    }


}
