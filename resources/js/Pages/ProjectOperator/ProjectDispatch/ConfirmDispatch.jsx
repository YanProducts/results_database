import Layout from "../../../Layout/Layout";
import useComfirmDispatchActions from "../../../Action/ProjectOperator/useConfirmDispatchActions";
import useConfirmDispatchDefinitions from "../../../Definition/ProjectOperator/useComfirmDispatchDefinitions";
import ToggleLists from "../../../Components/Common/TuggleLists";
import SubmitOrBackButtons from "../../../Components/Common/SubmitOrBackButtons";

// 重複可能性のある案件をどうするかの確認
export default function ConfirmDispatch({what,type,prefix,sameProjectData,sameTownsData}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useConfirmDispatchDefinitions();

  // 動き
  const {onProjectsCheckClick,onSubmitBtnClick,onCancelBtnClick}=useComfirmDispatchActions(post,data,setData);


  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>

    {/* 投稿フォーム */}
    <form>
      <p>　</p>
      <h1 className="base_h base_h1 min-w-180 max-w-300">重複データ確認</h1>

    <div className="base_frame min-w-180 max-w-300 bg-gray-300 pt-3 pb-1 border-2 border-black rounded-sm mb-5">

      <div className="base_backColor base_frame min-w-160px text-left mt-5 mb-10">
        <h2 className="base_h">１：案件データの重複</h2>
        {sameProjectData ?
        <>
          <p>下記の案件が、前回の同名の案件から１ヶ月が経過しておりますが、同じ案件でしょうか？</p>
          <ToggleLists formLists={data.newProject} contents={sameProjectData} onToggleListsChange={onProjectsCheckClick}/>
        </>
        :
        <p>前回の案件の終了予定日から1か月以上が経過している重複案件データはありません(1月以内の場合、自動的に同じ案件を見なされます)</p>
        }
      </div>

      <div className="base_frame min-w-160px text-left mt-5 mb-10">
        <h2 className="base_h">２：町目データの重複</h2>
        {sameTownsData ?
        <p>下記の案件の下記の町目では、同案件名の最新のバージョンでは既に町目を振り終えております。<br/>分割したなどの特殊状況で間違いないかを確認し、決定かやり直すかを選択してください</p>
        :
        <p>同案件の最終版には、既に割り振りを終えている町目はありません。重複案件データはありません</p>
        }

      </div>
    </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 決定/やり直すの選択ボタン */}
      <SubmitOrBackButtons processing={processing} onSubmitBtnClick={onSubmitBtnClick} onCancelBtnClick={onCancelBtnClick}/>

    </form>
    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </RoleLayout>
    </Layout>
  )
}
