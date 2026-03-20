import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import useComfirmDispatchActions from "../../../Action/ProjectOperator/useConfirmDispatchActions";
import useConfirmDispatchDefinitions from "../../../Definition/ProjectOperator/useComfirmDispatchDefinitions";
import ToggleLists from "../../../Components/Common/ToggleLists";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import SubmitOrBackButtons from "../../../Components/Common/SubmitOrBackButtons";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";

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

      <div className={`base_backColor base_frame border-2 border-black  text-left mt-5 mb-10 p-2 min-w-140`}>
        <h2 className="base_h">１：案件データの重複</h2>
        {sameProjectsData ?
        <>
          <p className="px-2">下記の案件が、前回の同名の案件から１ヶ月が経過しておりますが、同じ案件でしょうか？</p>
          <ToggleLists contents={sameProjectsData} onToggleListsChange={onProjectsCheckClick} formLists={data.newProjects} labelWhenTrue="新案件" labelWhenFalse="既存と同じ"/>
        </>
        :
        <p>前回の案件の終了予定日から1か月以上が経過している重複案件データはありません(1月以内の場合、自動的に同じ案件を見なされます)</p>
        }
      </div>

      <div className={`base_backColor base_frame border-2 border-black  text-left mt-5 mb-10 p-2 min-w-140`}>
        <h2 className="base_h">２：町目データの重複</h2>
        {sameTownsData ?
        <>
            <p>下記の案件の下記の町目では、同案件名の最新のバージョンでは既に町目を振り終えております。<br/>分割したなどの特殊状況で間違いないかを確認し、決定かやり直すかを選択してください</p>
            <div className="mt-5 text-center">
                 <div className="flex border bg-orange-200 border-black border-collapse">
                        <span className="inline-block border px-5  borer-black box-border border-collapse font-bold w-2/5">案件名</span>
                        <span className="inline-block border border-black box-border border-collapse font-bold w-3/5">町丁目名</span>
                  </div>
            {
                sameTownsData.map((eachTownData,index)=>
                  <div key={index} className="flex border bg-lime-200 border-black border-collapse">
                        <span className="inline-block border px-5  borer-black box-border border-collapse w-2/5">{eachTownData.projectName}</span>
                        <span className="inline-block border border-black box-border border-collapse w-3/5">{eachTownData.address}</span>
                  </div>
                )
            }
            </div>
        </>

        :
        <p>同案件の最終版には、既に割り振りを終えている町目はありません。重複案件データはありません</p>
        }

      </div>
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
