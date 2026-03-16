import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import InputPageHeader from "../../../Components/Common/InputPageHeader";
import SelectParts from "../../../Components/Common/SelectParts";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useSendProjectDefinitions from "../../../Definition/ProjectOperator/useSendProjectDefinitions";
import useSendProjectActions from "../../../Action/ProjectOperator/useSendProjectActions";
import InputFiles from "../../../Components/Common/InputFiles";

// 案件を営業所担当に送信
export default function SendProjectToBranch({prefix,what,type,placeSets}){


  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useSendProjectDefinitions();

  // 動き
  const {onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}=useSendProjectActions(post,data,setData);

  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>


    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
            {/* 選択項目 */}
            <InputPageHeader what={what} type={type} specialMessage="以下を選択してください"/>

             <div className="base_frame min-w-110 max-w-160 base_backColor pt-3 pb-1 border-2 border-black rounded-sm mb-5">

            {/* 営業所名 */}
            <SelectParts name="place" value={data.place} onChange={onPlaceChange} prefix={"営業所名："} keyValueSets={placeSets} allowEmptyOption={false}/>

            {/* ファイル */}
            <InputFiles name="fileSets"
            onChange={onFileChange} prefix="案件CSV(複数可)："
            attributes={{multiple:true}}
            data={data}
            onFileDeleteClick={onFileDeleteClick}
            />

            </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton processing={processing}/>
    </form>
    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </RoleLayout>
    </Layout>
  )
}

