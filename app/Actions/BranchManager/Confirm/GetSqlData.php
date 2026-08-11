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

        // 条件に合う住所の[id=>住所]のリストを返す(住所名はviewで必要)
        $address_id_sets=self::get_filtered_address_data($params);

        // 条件１：配布期間、条件２：住所データにおける、条件３：メイン案件の全配布結果のクエリを取得（開始年度が無制限の場合は無制限）し、①スタッフ全員②その営業所のスタッフ③該当スタッフ(指定されていれば)の配布結果を返す
        $query_by_staff_pattern_collects=self::get_filtered_query_sets($params,$address_id_sets);

        // 取得したクエリからコレクションを取得
        // その際に、UI用に計算された値と住所の名前も挿入
        $data_by_staff_patterns=self::get_formatted_data_collection($query_by_staff_pattern_collects,$address_id_sets);

        // スタッフのパターンにより3つの要素の配列に
        // それぞれの内部では住所のidをキーにとり、結果のデータ($result_sets)と住所の名前の配列
        return $data_by_staff_patterns;
    }

    // 条件に合う住所のidと名前のセットを返す
    public static function get_filtered_address_data($params){

        return match($params->pattern){
            "list"=>AddressHelpers::get_id_name_sets_from_name_array($params->address_names)->toArray(),
            "selectAll"=>AddressHelpers::get_all_town_data_in_the_city($params->pref,$params->city)->toArray(),
            "selectOneTown"=>[$params->address_id=>AddressHelpers::get_city_and_town_from_id($params->address_id)],
            default=>throw new BusinessException("予期せぬパターンです")
        };
    }

    // 条件１：配布期間、条件２：住所データにおける、条件３：メイン案件の全配布結果のクエリを取得（開始年度が無制限の場合は無制限）し、①スタッフ全員②その営業所のスタッフ③該当スタッフ(指定されていれば)の配布結果を返す
    public static function get_filtered_query_sets($params,$address_id_sets){

        // 条件１：配布期間&住所データにおける、条件２：メイン案件の全配布結果のクエリを取得（開始年度が無制限の場合は無制限）
        $range_query=DistributionRecordHelpers::get_query_of_all_records_by_year_range($start_year=$params->start_year,$params->end_year,$start_year==Date::ResultSerachBeforeYearLimit+1)->whereIn("address_id",array_keys($address_id_sets))->whereHas("distribution_plan",function($query){$query->where("main_id",null);});

        // 上記の住所id、特定配布期間における①スタッフ全員②その営業所のスタッフ③該当スタッフ(指定されていれば)の配布結果を返す
        return collect([...(!empty($params->staff_ids) ? ["selected_staffs"=>(clone $range_query)->whereIn("staff_id",$params->staff_ids)] : []),
        "all_staffs_in_the_places"=>self::get_all_data_from_query_in_place(clone $range_query),"all_staffs"=>(clone $range_query)]);
    }

    // フォーマットされたコレクションの取得
    public static function get_formatted_data_collection($query_by_staff_pattern_collects,$address_id_sets){


        return $query_by_staff_pattern_collects->map(fn($each_query)=>
        // 町目をキーに配布枚数を取得(後に列挙しながら集計する)
        ($each_query->selectRaw("address_id,distribution_count")->get()->groupBy("address_id"))->map(function($data,$id)use($address_id_sets){
            $result_sets=$data->pluck("distribution_count");
            return[
                "address_name"=>$address_id_sets[$id],
                "average"=>round($avg=$result_sets->avg(),1), //平均値
                "max"=>$result_sets->max(), //最大値
                "center"=>$result_sets->median(),//中央値
                "stddev"=>round(sqrt(($result_sets->map(fn($result)=>($result-$avg)**2))->avg()),2), //標準偏差
                "all_past_data"=>$result_sets->implode(","), //すべての値の列挙
                ];
        }));
    }

    // その営業所における過去の記録の全データ(複数モデルを呼び出し、特殊性も高いので、DistributionRecordのヘルパーではなく呼び出し側の部分関数で定義)
    public static function get_all_data_from_query_in_place($range_query){

       return $range_query->whereIn("staff_id",FieldStaffList::where("place_id",BranchManagerList::findOrFail(Auth::user()->authable_id)->place_id)->pluck("id"));
    }




}
