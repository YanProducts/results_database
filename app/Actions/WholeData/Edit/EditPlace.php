<?php

// 営業所の情報変更
namespace App\Actions\WholeData\Edit;

use App\Exceptions\BusinessException;
use App\Models\Place;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditPlace{
    // 実際のsql更新
    //パラメータ自体が変わるためrequestで引き受ける
    public static function update_place_sql($request){

        try{
            DB::transaction(function()use($request){
                // モデル取得(バリデーション済。idは必ず存在)
                $place=Place::findOrFail($request->id);

                // パラメータがあれば更新
                foreach(["place_name","is_active","red","green","blue"] as $parameter){
                    if($request->exists($parameter)){
                        $place[$parameter]=$request[$parameter];
                    }
                }
                $place->save();
            });
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("更新の際にエラーが発生しました");
        }
    }
}
