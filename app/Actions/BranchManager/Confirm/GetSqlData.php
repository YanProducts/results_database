<?php
// 過去の記録の参照におけるsqlデータの取得
namespace App\Actions\BranchManager\Confirm;

use App\Constants\Date;
use App\Exceptions\BusinessException;
use App\Models\BranchManagerList;
use App\Models\FieldStaffList;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use Illuminate\Support\Facades\Auth;
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

    // 検索されたSQLデータの取得
    public static function get_filtered_sql_data($params){
        // 条件に合う住所のidリストを返す
        $address_ids=match($params->pattern){
            "list"=>AddressHelpers::get_id_lists_from_town_names($params->address_names),
            "selectAll"=>AddressHelpers::get_all_town_data_in_the_city($params->pref,$params->city),
            "selectOneTown"=>$params->address_id,
            default=>throw new BusinessException("予期せぬパターンです")
        };

        // ある配布期間&住所データにおける全配布結果のクエリを取得（開始年度が無制限の場合は無制限）
        $start_year=$params->start_year;
        $range_query=DistributionRecordHelpers::get_query_of_all_records_by_year_range($start_year,$params->end_year,$start_year==Date::ResultSerachBeforeYearLimit+1)->where("address_id",$address_ids);

        // 上記の住所id、特定配布期間における①スタッフ全員②その営業所のスタッフ③該当スタッフ(指定されていれば)の配布結果を返す
        $query_by_staff_pattern_collects=collect($range_query,self::get_all_data_from_query_in_place($range_query),$range_query->whereIn("staff_id",$params->staff_ids));

        $query_by_staff_pattern_collects->map(fn($each_query)=>
        // スタッフ・範囲・町目で絞った項目を、町目ごとに集計化
        $each_query->selectRaw("address_id,sum(distribution_count)")->groupBy("address_id")->get()
        );

        return $query_by_staff_pattern_collects->toArray();

    }

    // その営業所における過去の記録の全データ(複数モデルを呼び出し、特殊性も高いので、DistributionRecordのヘルパーではなく呼び出し側の部分関数で定義)
    public static function get_all_data_from_query_in_place($range_query){
       return $range_query->whereIn("staff_id",FieldStaffList::whereIn("place_id",BranchManagerList::findOrFail(Auth::user()->authable_id)->place_id));
    }


}
