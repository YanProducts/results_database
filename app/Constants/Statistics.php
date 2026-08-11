<?php

namespace App\Constants;

// 統計に関する定数
class Statistics{
    // 現時点で解析を行う県
    public const Prefectures=["hyogo"=>"兵庫県","osaka"=>"大阪府"];

    // 「〜丁目」「〜丁」で終了している市
    public const ChoEndCities=["堺市"];

    // 住所ではないor人が住んでいない可能性の高いちめいリスト
    public const INVALID_TOWN_REGEXP=["水面|河川|港湾|埠頭|空港|山林"];

}
