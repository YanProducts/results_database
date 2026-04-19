<?php
// 重複チェックに関するメソッド
namespace App\Actions\BranchManager\Assgin\CheckAssign;

class Read{
    // 今回新たに振られたplan_idがこれまでのデータに入っているかの重複確認(同じ町を2人で分けたときなどがあるので自然にアウトはさせない)
    public static function duplicated_data_check($all_data){

        // 重複データ同時を返す(それぞれ重複しているデータの内容が入る)
        return ["duplicated_in_sql"=>self::duplicated_check_in_sql_data($all_data),"duplicated_in_post"=>self::duplicated_check_in_post_data($all_data)];
    }


     // 今回とsql内部での重複
    public static function duplicated_check_in_sql_data($all_data){

        return [];
    }

     // 今回のデータ同士の重複
    public static function duplicated_check_in_post_data($all_data){

        return [];
    }
}
