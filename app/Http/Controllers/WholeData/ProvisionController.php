<?php

namespace App\Http\Controllers\WholeData;

use App\Actions\WholeData\Edit\EditUser;
use App\Actions\WholeData\Places;
use App\Actions\WholeData\Provision;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\WholeData\ProvisionRequest;

use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use App\Support\WholeData\DecideEditItemValidation;
use App\Utils\Session;

// wholeDataから各ユーザーの名前を登録していくコントローラー(パスワード等は別途Authで格納)
class ProvisionController extends Controller
{

    // ユーザー名とroleなどを事前登録するコントローラー
    public function provision(){
        // すべてのroleを英語=>日本語の配列で取得(営業所が登録されていない場合の現場と営業所担当は除く)
        $role_sets=UserRoleResolver::get_all_values();
        return Inertia::render("WholeData/Provision",[
            "type"=>"ユーザーの登録",
            // roleの取得(英語=>日本語の配列)
            "roleSets"=>$role_sets,
            // 「営業所が事前登録されていない」というアナウンス
            "nonPlaceAlert"=>!array_key_exists("field_staff",$role_sets),
            // 現在登録されている営業所を取得
            "placeSets"=>PlaceHelpers::get_registered_places(),
        ]);
    }
    // ユーザー名とroleなどを事前登録するコントローラー(post)
    public function provision_post(ProvisionRequest $request){
        // 登録
        Provision::provision_each_role_users($request);
        // 確認
        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"whole_data.admin_overview",
        "routeParams"=>["type"=>"all"],
        "linkPageInJpn"=>"登録状況確認"]);
    }

    // 編集ユーザーの決定
    public function decide_edit_user($role,$id){
        //  roleとidの例外除去
        if($messages=DecideEditItemValidation::decide_change_user_validation($role,$id)) {
            return redirect()
                ->back()
                ->withErrors($messages);
        }

        // 表示
        return Inertia::render("WholeData/EditUser",[
            "type"=>"ユーザーの編集",
            "userInformation"=>EditUser::get_user_information($role,$id),//ユーザーの情報
            "placeLists"=>in_array($role,["field_staff","branch_manager"]) ? PlaceHelpers::get_registered_places(): [] //営業所候補名リスト(id=>営業所名)
        ]);
    }

}
