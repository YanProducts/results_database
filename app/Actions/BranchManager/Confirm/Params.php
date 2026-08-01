<?php

// 過去の町目ごとのデータ確認におけるパラメータ
namespace App\Actions\BranchManager\Confirm;
class Params{
    // 引数に渡すと同時にプロパティに代入
    public function __construct(
        public array $staff_ids,
        public int $start_year,
        public int $end_year,
        public int $pattern,
        public string $pref,
        public string $city,
        public int $address_id,
        public array $address_names,
    ) {}
}
