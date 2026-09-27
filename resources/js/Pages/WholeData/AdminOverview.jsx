import useAdminOverviewDefinitions from "../../Definition/WholeData/useAdminOverviewDefinitions";
import useAdminOverviewActions from "../../Action/WholeData/useAdminOverviewActions";
import Layout from "../../Layout/Layout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import BaseTable from "../../Components/Common/BaseTable";
import { RoleLayout } from "../../Layout/RoleLayout";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import UserTableLists from "../../Components/Part/WholeData/Overview/UserTableLists";

// 全体統括者が、個々のユーザーを登録していくページ
//useFormは使用しないが、LaravelでbackからWithErrorsを行なっているのでInertiaから渡されるerrorsをuseFormとは無関係に取得
export default function AdminOverview({what,type,prefix,userDataSets,userKeyInJpn,placeDataSets,placeKeyInJpn,errors}){

  // 定義(変更/削除のフォームなど)
  const {allPrioritySets,setAllPrioritySets,pageMinWidth,pageMaxWidth}=useAdminOverviewDefinitions({});

  // 動き
  const {onChangeUserDecideClick,onChangePlaceDecideClick}=useAdminOverviewActions({setAllPrioritySets});

  return(
    <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>
        <p>　</p>
        <h1 className={`base_h base_h1 ${pageMinWidth} ${pageMaxWidth}`}>全般統括-{type}-</h1>

        {/* バリデーションエラー */}
        <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>

        {/* ユーザーのテーブル */}
        {type!=="営業所" &&
            <UserTableLists {...{userDataSets,userKeyInJpn,onChangeUserDecideClick,allPrioritySets,pageMaxWidth,pageMinWidth}}/>
        }

        {/* 営業所のテーブル */}
        {type!=="ユーザー" &&
        <div className={`mt-10 ${pageMaxWidth} ${pageMinWidth}`}>
          <BaseTable tableTheme="営業所" allData={placeDataSets} thSets={placeKeyInJpn} plusTrCss="cursor-pointer hover:border-4 border-sky-400" editFunc={onChangePlaceDecideClick}         plusTrCssFunc={(eachData) => !eachData.is_active && "bg-gray-300"} editFuncParam="place" editFuncKey="id" minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>
        </div>
        }

    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.register_places"  what="営業所の登録"/>
        <BaseLinkLine routeName="whole_data.provision"  what="ユーザーの事前登録"/>
        <BaseLinkLine routeName={`${prefix}.logout`} what="ログアウト"/>
      </div>
      <p>　</p>
     </RoleLayout>
    </Layout>
  )
}

