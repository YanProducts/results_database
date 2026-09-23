//全体統括者が個々のユーザーを登録していく動き
import { router } from '@inertiajs/react';
import React from 'react';
import {route} from 'ziggy-js';
export default function useAdminOverviewActions(data,setData,post){

  // 変化させるユーザーの決定ボタンを押した時
  // パラメータ名は共通関数に合わせている
  const onChangeUserDecideClick=({paramForType,paramByEachData})=>{
    router.get(route("whole_data.decide_edit_place",{
        "role":paramForType,
        "id":paramByEachData
    }))
  }

 //  変化させる営業所決定のボタンを押した時
  const onChangePlaceDecideClick=({paramByEachData})=>{
    router.get(route("whole_data.decide_edit_place",{
        "id":paramByEachData
    }))
    // その要素がどこかを取得
    // setData({
    //     "type":"user",
    //     "placeId":paramByEachData //ユーザーの名前(no2など)
    // })
  }

  return{onChangeUserDecideClick,onChangePlaceDecideClick}
}
