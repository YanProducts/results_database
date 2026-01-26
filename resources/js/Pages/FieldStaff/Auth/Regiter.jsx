// スタッフの登録ページ
import Layout from "../../../Layout/Layout";
import useAuthDefinitions from "../../../Definition/Common/Auth/useAuthDefinitions";
import useAuthActions from "../../../Action/Common/useAuthActions";
import AuthInput from "../../../Components/Whole/AuthInput";
import ViewValidationErrors from "../../../Components/Whole/ViewValidationErrors";
import BaseButton from "../../../Components/Whole/BaseButton";

export function Register(){

  // 定義(フォーム)
  const { data, setData, post, processing, errors, reset}=useAuthDefinitions();

  // 動き
  const {onUserChange,onPassChange,onPassConfirmChange,onSubmitBtnClick}=useAuthActions(setData,post,"/field_staff/register");

  return(
    <Layout title="スタッフ登録">
      <h1 className="base_h">ユーザー名とパスワードを入力してください</h1>
      <div className="base_frame">
        {/* dataには以前の値も入力される */}
        <AuthInput type="text" name="userName" value={data.userName} onChange={onUserChange} />
        <AuthInput type="password" name="passWord" value={data.passWord} onChange={onPassChange} />
        <AuthInput type="password" name="passWordConfirmation" value={data.passWordConfirmation} onChange={onPassConfirmChange} />
      </div>
      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton onSubmitBtnClick={onSubmitBtnClick} processing={processing}/>

    </Layout>
  )
}