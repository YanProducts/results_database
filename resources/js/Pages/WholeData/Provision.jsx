import useProvisionDefinitions from "../../Definition/WholeData/useProvisionDefinitions";
import useProvisionActions from "../../Action/WholeData/useProvisionActions";
import Layout from "../../Layout/Layout";
import InputPageHeader from "../../Components/Common/InputPageHeader";
import InputParts from "../../Components/Common/InputParts";
import SelectParts from "../../Components/Common/SelectParts";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import BaseButton from "../../Components/Common/BaseButton";
import { Link } from "@inertiajs/react";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";

// 全体統括者が、個々のユーザーを登録していくページ
export default function Provision({roleSets,what}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useProvisionDefinitions();

  // 動き
  const {onUserChange,onRoleChange,onPlaceChange,onStaffNameChange,onSubmitBtnClick}=useProvisionActions(setData,post,"whole_data.select_post");

  return(
    <Layout title="全般統括-メンバー登録-">
     <div className="h-full min-h-screen bg-lime-200">

    {/* タイトル */}
    <InputPageHeader what={what} type="" inputWhat="下記"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
             <div className="base_frame min-w-80 max-w-100 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">

                {/* roleの選択 */}
                <SelectParts name="role" value={data.role} onChange={onRoleChange} prefix={"職種："} keyValueSets={roleSets}/>

                {/* ユーザー名 */}
                <InputParts type="text" name="userName" value={data.userName} onChange={onUserChange} prefix="ユーザー名："/>

                {/* 営業所 */}
                <InputParts type="text" name="place" value={data.place} onChange={onPlaceChange} prefix={"営業所名："} />

                {/* スタッフ名 */}
                <InputParts type="text" name="staffName" value={data.staffName} onChange={onStaffNameChange} prefix={"スタッフ名："}/>
              </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton processing={processing}/>
    </form>
        <BaseLinkLine href="whole_data/logout"  what="ログアウト"/>
    </div>
    </Layout>
  )
}

