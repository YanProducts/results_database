// スタッフの登録ページ
import Layout from "../../Layout/Layout";
import useAuthDefinitions from "../../Definition/Common/Auth/useAuthDefinitions";
import useAuthActions from "../../Action/Auth/useAuthActions";
import FormSets from "../../Components/Part/Auth/FormSets";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import BaseButton from "../../Components/Common/BaseButton";
import InputPageHeader from "../../Components/Common/InputPageHeader";

export default function Register({pageNameSets}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset,prefix,what}=useAuthDefinitions(pageNameSets);

  // 動き
  const {onUserChange,onPassChange,onPassConfirmChange,onEmailChange,onNewPassChange,onNewPassConfirmChange,onSubmitBtnClick}=useAuthActions(setData,post,prefix + ".register_post");

  return(
    <Layout title={`${what}新規登録`}>
     <div className="h-full min-h-screen bg-sky-300">

    {/* タイトル */}
    <InputPageHeader what={what} type="新規登録" inputWhat="下記"/>

    {/* 投稿フォーム */}
    <FormSets formType={"Register"} role={prefix} data={data} onUserChange={onUserChange}onPassChange={onPassChange} onPassConfirmChange={onPassConfirmChange} onEmailChange={onEmailChange}
    // 新しいパスワード系列はregisterページでは呼び出されない
    />

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton onSubmitBtnClick={onSubmitBtnClick} processing={processing}/>

    </div>
    </Layout>
  )
}
