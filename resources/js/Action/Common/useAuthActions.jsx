// ログイン操作周りの動き
// use~的な定義がないからファイル名にuseは不要だが、inertia系列の操作も行うので、わかりやすさのためにつける

export default function useAuthActions({setData,post,url}){

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
    setData("passWordConfirmation",e.currentTarget.value)
  } 

  // 決定ボタンを押した時
  const onSubmitBtnClick=()=>{
    // バリデーションはlaravelに任せる(遷移しないため)
       post(url);
  }

  return{onUserChange,onPassChange,onPassConfirmChange,onSubmitBtnClick}
}