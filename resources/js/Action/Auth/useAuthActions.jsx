// ログイン操作周りの動き
// use~的な定義がないからファイル名にuseは不要だが、inertia系列の操作も行うので、わかりやすさのためにつける

import {route} from 'ziggy-js';
export default function useAuthActions(setData,post,routeName){

  // ユーザー名変化
  const onUserChange=(e)=>{
    // setDataはprevStateしなくとも内部でマージする
    setData("userName",e.currentTarget.value);
  }
  // パスワード変化
  const onPassChange=(e)=>{
    setData("passWord",e.currentTarget.value)
  }

  // パスワード確認変化（不要な時は使わなければ良いだけ）
  const onPassConfirmChange=(e)=>{
    setData("passWord_confirmation",e.currentTarget.value)
  }

 // メールの変化（不要な時は使わなければ良いだけ）
  const onEmailChange=(e)=>{
    setData("email",e.currentTarget.value)
  }

  // 新しいパスワード変化（不要な時は使わなければ良いだけ）
  const onNewPassChange=(e)=>{
    setData("newPassWord",e.currentTarget.value)
  }

  // 新しいパスワード確認変化（不要な時は使わなければ良いだけ）
  const onNewPassConfirmChange=(e)=>{
    setData("newPassWord",e.currentTarget.value)
  }


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
    e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route(routeName));
  }

  return{onUserChange,onPassChange,onPassConfirmChange,onEmailChange,onNewPassChange,onNewPassConfirmChange,onSubmitBtnClick}
}
