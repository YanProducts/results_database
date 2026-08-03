<?php

// 過去の町目ごとのデータ確認におけるパラメータ
namespace App\Actions\BranchManager\Confirm;

use App\Constants\Date;
use App\Models\FieldStaffList;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;

class Params{
    // 引数に渡すと同時にプロパティに代入
    public function __construct(
        public array $staff_ids,
        public int $start_year,
        public int $end_year,
        public string $pattern,
        public ?string $pref,
        public ?string $city,
        public ?int $address_id,
        public ?array $address_names,
    ) {}

    public function get_string_for_UI(){

        return[
            "staff_names"=>FieldStaffListHelpers::get_staff_names_from_id_array($this->staff_ids),
            "date_range"=>($this->start_year ==Date::ResultSerachBeforeYearLimit+1 ? "制限なし" : $this->start_year)."〜".($this->end_year==0 ? "現在" : $this->end_year)
        ];

    }
}
