<?php

namespace App\Support\WholeData;

// ユーザーの登録情報を変更するバリデーションに追加するruleのバリデーション
use App\Models\FieldStaffList;
use App\Rules\WholeData\PlaceExistsRule;
use App\Rules\WholeData\StaffNameRule;
use Illuminate\Validation\Rule;

class UpdateUserValidation{
    public static function added_rule_by_cases($data){
            // roleが営業所担当もしくは現場担当の時は編集データが存在する場合は必ず営業所IDと一致(sometimesでキー時代が存在しない場合はその他の検証を行わない)
            // roleが営業所と現場以外の時は営業所名は存在しては行けない
            $rule["placeId"]=in_array($role=($data["role"] ?? null),["field_staff","branch_manager"])  ? ["sometimes","integer",new PlaceExistsRule]: ["missing"];


            // roleがfield_staffの時はスタッフ名
            // パラメータなし＝変化なしも許容(sometimes=パラメータ自体がない場合は検証しない)、パラメータありで空白に変更も許容(nullで読まれる場合があるから、データベース書き込みの際に空白に変更することに注意)、空白ではない場合は同じ営業所の場合に名前が重ならないこと
            $data_id=$data["id"] ?? null;
            $rule["staffName"]=$role=="field_staff" ? ["sometimes","nullable","string",new StaffNameRule,Rule::when(!empty($data["staffName"]),Rule::unique("field_staff_lists","staff_name")->where(fn($query)=>$query->where("place_id",($data["placeId"] ?? FieldStaffList::find($data_id)?->place_id)))->ignore($data_id))//ignoreで指定したidを対象から外す(つまり自分自身が変更後も同じ名前のまま、誤って投稿された場合は、変更(=結果的には同じ値だが)可能とする)
            ]: ["missing"];

            return $rule ?? [];
    }
}
