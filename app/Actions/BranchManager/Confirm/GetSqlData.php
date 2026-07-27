<?php
// 過去の記録の参照におけるsqlデータの取得
namespace App\Actions\BranchManager\Confirm;

use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use Illuminate\Support\Facades\Log;

class GetSqlData{
    public static function get_reference_sql_data(){

        // ログインしている営業所担当の営業所id
        $place_id=BranchManagerListHelpers::get_login_user_place_id();

        // 上記の営業所におけるスタッフのリスト(id=>スタッフ名で返る)
        $staff_lists=FieldStaffListHelpers::get_all_names_of_staffs_in_the_place($place_id);

        // 県=>市=>町の入れ子配列で住所が返る
        $address_lists=AddressHelpers::get_all_address_lists();

        // 受け取り先の共通コンポーネントに合わせてidとnameForUIをセットする
        return [$staff_lists->mapWithKeys(fn($each_staff_list)=>([$each_staff_list->id=>["id"=>$each_staff_list->id,"nameForUI"=>$each_staff_list->staff_name]])),$address_lists];
    }
}
