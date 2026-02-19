//全体統括者が個々のユーザーを登録していく動き

import {route} from 'ziggy-js';
export default function useRegisterPlacesActions(setData,post,routeName){

  // 場所変化(不要なら使わないだけ)
  const onPlaceChange=(e)=>{
    setData("place",e.currentTarget.value)
  }

 // 色の変化の変化（不要な時は使わなければ良いだけ）
  const onColorValueChange=(e)=>{
    setData("colors",{
        "red":e.currentTarget.value,
        "green":e.currentTarget.value,
        "blue":e.currentTarget.value,
    })
  }

  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
    e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
    post(route(routeName));
  }

  return{onPlaceChange,onColorValueChange,onSubmitBtnClick}
}
