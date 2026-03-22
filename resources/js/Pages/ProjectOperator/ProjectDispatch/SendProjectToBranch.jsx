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
  const { data, setData, post, processing, errors, reset,pageMinWidth,pageMaxWidth}=useSendProjectDefinitions();

  // 動き
  const {onPlaceChange,onFileChange,onFileDeleteClick,onSubmitBtnClick}=useSendProjectActions(post,data,setData);

  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>


    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
            {/* 選択項目 */}
            <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} specialMessage="以下を選択してください"/>

             <div className={`base_frame ${pageMinWidth} ${pageMaxWidth} base_backColor pt-3 pb-1 border-2 border-black rounded-sm mb-5`}>

            {/* 営業所名 */}
            <SelectParts name="place" value={data.place} onChange={onPlaceChange} prefix={"営業所名："} maxWidth="max-w-140"
            minWidth="min-w-75" prefixPercent="w-[40%]" keyValueSets={placeSets} allowEmptyOption={false}/>

            {/* ファイル */}
            <InputFiles name="fileSets" minWidth="min-w-90"
            onChange={onFileChange} prefix="案件CSV(複数可)："
            attributes={{multiple:true}}
            data={data}
            onFileDeleteClick={onFileDeleteClick}
            />

            </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>

      {/* 提出ボタン */}
      <BaseButton processing={processing} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>
    </form>

    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout" minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>
    </RoleLayout>
    </Layout>
  )
}

