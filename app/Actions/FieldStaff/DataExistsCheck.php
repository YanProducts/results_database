<?php

// その日、そのスタッフのデータが概に存在するか
namespace App\Actions\FieldStaff;

use App\Support\Common\ModelHelpers\DistributionRecordHelper;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use Illuminate\Support\Facades\Auth;

class DataExistsCheck{

    // その日のそのスタッフのデータがすでに存在するか
    public static function data_exists_check($date,$staff_id){
        $duplicated_sets=DistributionRecordHelpers::data_in_the_date_and_staff($date,$staff_id);

        if($duplicated_sets->isNotEmpty()){
            // return [
                // "duplicateReport" => [
                    // 既に提出されたデータ
                    // "project_name"=>[
                    //     "address_name"=>"",
                    //     "main_counts"=>"",
                    //     "sub_sets"=>[
                    //         "sub_projct_name"=>"",
                    //         "sub_counts"=>""
                    //     ]
                    // ],
                // ],
            // ]);
            // 実験用
            return ["aaa"=>"bbb"];
        }else{
            return null;
        }

    }

}
