//全体統括者が個々のユーザーを登録していく動き

import {route} from 'ziggy-js';
export default function useAdminOverviewActions(setData,post){

// ロール変化
const onRoleChange=(e)=>{
    setData("role",e.currentTarget.value)
}

  // ユーザー名変化
  const onUserChange=(e)=>{
    // setDataはprevStateしなくとも内部でマージする
    setData("userId",e.currentTarget.value);
  }

  // 場所変化(不要なら使わないだけ)
  const onPlaceChange=(e)=>{
    setData("placeId",e.currentTarget.value)
  }


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
    e.preventDefault();
    // その要素がどこかを取得

    // formに値のセット


       post(route("whole_data.update_user",));
       post(route("whole_data.delete_user",));

       post(route("whole_data.update_place",));
       post(route("whole_data.delete_place",));
  }

  return{onUserChange,onRoleChange,onPlaceChange,onSubmitBtnClick}
}
