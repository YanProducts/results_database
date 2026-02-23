<?php
namespace App\Actions\WholeData;

use App\Exceptions\BusinessException;
use App\Support\Auth\UserRoleResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//各ロールの登録
class Provision{
    // ロールの登録(バリデーション済)
    public static function provision_each_role_users($request){

        DB::transaction(function()use($request){
            // roleとユーザー名の取得(全てで複数回使う)
            $role=$request->role;
            $user_name=$request->userName;
            // roleからモデルの名前空間名を取得
            $model_namespace=UserRoleResolver::get_model_from_route($role);
            // バリデーション済だが、作成中に同タイミングで別の人が作成しにかかった時を考慮
            if($model_namespace::where("user_name",$user_name)->exists()){
                throw new BusinessException("同じ名前が登録されています");
            }

            // 各インスタンスを取得し、事前登録(その後パスワードなどはuserAuthで登録)
            $model_instance=new $model_namespace();
            $model_instance->user_name=$user_name;

            if(in_array($role,["field_staff","branch_manager"])){
                $model_instance->placeId=$request->place ?? null;
            }
            if($role=="field_staff"){
                $model_instance->staff_name=$request->staffName ?? "";
            }

            $model_instance->save();
        });

    }
}
