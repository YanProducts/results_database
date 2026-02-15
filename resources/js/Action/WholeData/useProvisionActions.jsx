//全体統括者が個々のユーザーを登録していく動き

import {route} from 'ziggy-js';
export default function useProvisionActions(setData,post,routeName){

// ロール変化
const onRoleChange=(e)=>{
    setData("role",e.currentTarget.value)
}

  // ユーザー名変化
  const onUserChange=(e)=>{
    // setDataはprevStateしなくとも内部でマージする
    setData("userName",e.currentTarget.value);
  }

  // 場所変化(不要なら使わないだけ)
  const onPlaceChange=(e)=>{
    setData("place",e.currentTarget.value)
  }

 // スタッフの名前の変化（不要な時は使わなければ良いだけ）
  const onStaffNameChange=(e)=>{
    setData("staffName",e.currentTarget.value)
  }

  // 決定ボタンを押した時
  const onSubmitBtnClick=()=>{
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route(routeName));
  }

  return{onUserChange,onRoleChange,onPlaceChange,onStaffNameChange,onSubmitBtnClick}
}
