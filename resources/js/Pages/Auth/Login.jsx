// 全体ログインページ
import useAuthDefinitions from "../../Definition/Common/Auth/useAuthDefinitions";
import useAuthActions from "../../Action/Common/useAuthActions";
import FormSets from "../../Components/Part/Auth/FormSets";
import Layout from "../../Layout/Layout";
import ViewValidationErrors from "../../Components/Whole/ViewValidationErrors";
import BaseButton from "../../Components/Whole/BaseButton";

export default function Login({pageNameSets}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset, prefix,what}=useAuthDefinitions(pageNameSets);

 // 動き
 const {onUserChange,onPassChange,onPassConfirmChange,onEmailChange,onNewPassChange,onNewPassConfirmChange,onSubmitBtnClick}=useAuthActions(setData,post, prefix + ".login_post");

  return(
    <Layout title={`${what}ログイン`}>
     <div className="h-full min-h-screen bg-sky-300">

      {/* タイトル */}
        <AuthTitle what={what} type="ログイン" inputWhat="ユーザー名とパスワード"/>

        {/* 投稿フォーム */}
        <FormSets FormType={"Login"} role={prefix} data={data} onUserChange={onUserChange} onPassChange={onPassChange}
        // emailと新しいパスワード系列とパスワード確認はログインでは必要ない
        />

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton onSubmitBtnClick={onSubmitBtnClick} processing={processing}/>
    </div>
    </Layout>
  )
}
