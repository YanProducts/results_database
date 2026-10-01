<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use App\Models\BranchManagerList;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use Inertia\Inertia;

use App\Actions\ProjectOperator\Dispatch\CheckDispatch\Delete as CheckDelete;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use Illuminate\Http\Request;


// 案件を自分で登録する系統
class ProjectHandingController extends Controller
{
    // その営業所における案件の登録(最終的にProjectOperatorのページを使うので、内容自体はProjectDispatchと重なる面が多い。現状短く形式も微妙に異なるのでコピー)
    public function handing_assignment(){


        // これはcreateしたものがProjectOperatorだからだめ！

        // 現在の確認ページ用のsessionと、そのユーザーが投稿した「確認中」のテーブルは消去する
        // CheckDelete::automatic_delete_from_same_user();



        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            // その営業所の(Id=>名前)が取得(不要になる可能性あり)
            "placeSets"=>PlaceHelpers::get_place_name_from_ids([BranchManagerList::get_login_user_place_id()])->first(),
            "type"=>"案件→営業所"
        ]);
    }

    // その営業所における案件登録のpost
    public function handing_assignment_post(DispatchRequest $request){

    }

    // 重複した場合に戻す(postから自動でのget処理)

    // 重複した場合の登録


}
