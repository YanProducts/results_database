import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import useComfirmDispatchActions from "../../../Action/ProjectOperator/useConfirmDispatchActions";
import useConfirmDispatchDefinitions from "../../../Definition/ProjectOperator/useComfirmDispatchDefinitions";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import SubmitOrBackButtons from "../../../Components/Common/SubmitOrBackButtons";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import ProjectPart from "../../../Components/Part/ProjectOperator/ConfirmDispatch/ProjectPart";
import TownPart from "../../../Components/Part/ProjectOperator/ConfirmDispatch/TownPart";

// 重複可能性のある案件をどうするかの確認
export default function ConfirmDispatch({what,type,prefix,sameProjectsData,sameTownsData}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset,pageMinWidth,pageMaxWidth}=useConfirmDispatchDefinitions();

  // 動き
  const {onProjectsCheckClick,onSubmitBtnClick,onCancelBtnClick}=useComfirmDispatchActions(post,data,setData);


  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>

    {/* 投稿フォーム */}
    <form>
      <p>　</p>
      <h1 className={`base_h base_h1 ${pageMinWidth} ${pageMaxWidth}`}>重複データ確認</h1>

    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth} bg-gray-300 pt-3 pb-1 border-2 border-black rounded-sm mb-5`}>

     {/* 案件の操作(新案件or既存案件) */}
     <ProjectPart sameProjectsData={sameProjectsData} onProjectsCheckClick={onProjectsCheckClick} data={data}/>

    {/* 町目の確認 */}
     <TownPart sameTownsData={sameTownsData} data={data}/>

    </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 決定/やり直すの選択ボタン */}
      <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} onSubmitBtnClick={onSubmitBtnClick} onCancelBtnClick={onCancelBtnClick}/>

    </form>
    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName="whole_data.logout"  what="ログアウト"/>
      </div>

     <p>　</p>

    </RoleLayout>

    </Layout>
  )
}
