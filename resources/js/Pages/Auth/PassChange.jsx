// パスワードの変更
// ユーザー名は会社側でしか変更しない

import useAuthDefinitions from "../../Definition/Common/Auth/useAuthDefinitions";
import useAuthActions from "../../Action/Auth/useAuthActions";
import Layout from "../../Layout/Layout";
import FormSets from "../../Components/Part/Auth/FormSets";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import BaseButton from "../../Components/Common/BaseButton";
import InputPageHeader from "../../Components/Common/InputPageHeader";

export function PassChange({pageNameSets}){
 // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset,prefix,what}=useAuthDefinitions(pageNameSets);

 // 動き
 const {onUserChange,onPassChange,onPassConfirmChange,onEmailChange,onNewPassChange,onNewPassConfirmChange,onSubmitBtnClick}=useAuthActions(setData,post, prefix + ".pass_change_post");

  return(
    <Layout title={`${what}パスワード変更`}>
     <div className="h-full min-h-screen bg-sky-300">

        {/* タイトル */}
        <InputPageHeader what={what} type="パスワード変更" inputWhat="下記"/>

        <form onSubmit={onSubmitBtnClick}>
        {/* 投稿フォーム */}
        <FormSets FormType={"PassChange"} role={prefix} data={data} onUserChange={onUserChange} onPassChange={onPassChange}
        onNewPassChange={onNewPassChange} onNewPassConfirmChange={onNewPassConfirmChange}
        // emailとパスワード確認はパスワード変更には必要ない
        />

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton processing={processing}/>
      </form>

     </div>
    </Layout>
  )
}
