<?php

// ユーザーの情報変更
namespace App\Actions\WholeData\Edit;

use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Support\Auth\UserRoleResolver;
use App\Support\Common\ModelHelpers\UserAuthHelpers;
use App\Support\WholeData\OverViewHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditUser{
    // 対象ユーザーが決定し、そのユーザーの現在の情報を取得
    public static function get_user_information($role,$id){
        // モデルから取得
        $information=UserRoleResolver::get_user_information($model_name=UserRole::tryFrom($role)?->get_model_name(),$id);
        // その情報から必要なものをまとめる
        return[
            "role"=>$role,
            "id"=>$id,
            "userName"=>$user_name=$information->user_name,
            "staffName"=>$role=="field_staff" ? ($information->staff_name ??  $user_name ) : "",
            "placeId"=>in_array($role,["field_staff","branch_manager"]) ? $information->place_id : "", //idを取得し、別途全体で変更する時のことを考えてplace_nameを取得
            "registered"=>OverViewHelpers::get_register_status($id,$model_name), //登録自体が完了しているか(パスワードを設定しているか)
            "inWork"=>$information->is_active, //退職したユーザーか
            // 稼働状況が変化したか(送信するかの決定のために変化を比べる用)
            "isInWorkChange"=>false,
        ];
    }

    // ユーザーの実際の編集
    public static function change_user_data($data){
        // バリデーションは既にに終えている

        // この操作の間に何らかの変更(whole_dataは1人だから考えにくいが)がある場合はレアケースなので通常の例外処理を行う
        try{
            DB::transaction(function()use($data){

                // モデルの取得
                $model=UserRoleResolver::get_model_from_route($role=$data["role"]);
                // そのユーザー登録情報のインスタンス(バリデーション済だが念のためfindOrFailで除去)
                $user_model=$model::findOrFail($id=$data["id"]);

                // 現場か営業所の担当のとき
                if(in_array($role,["field_staff","branch_manager"]) && array_key_exists("placeId",$data)){
                   $user_model->place_id=$data["placeId"];
                }

                // 現場担当の時(パラメータが存在しない場合は登録しない)
                if($role=="field_staff" && array_key_exists("staffName",$data)){
                    // 文字列がある場合は更新。パラメータが送られているがnullの場合は空白で登録
                    $user_model->staff_name=$data["staffName"] ?? "";
                }

                // パラメータ存在=現在と変更
                if(array_key_exists("isInWorkChange",$data)){
                    $user_model->is_active=!$user_model->is_active;
                }

                // パスワード
                if(array_key_exists("registered",$data)){
                    // 必ず「リセットする」しかない
                    // リストには残しAuthから削除
                    UserAuthHelpers::reset_user_authentication($id,$model);
                }

                $user_model->save();
            });
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("データ更新の際に異常が生じました");
        }
    }


}
