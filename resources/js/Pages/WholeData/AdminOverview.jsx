import useAdminOverviewDefinitions from "../../Definition/WholeData/useAdminOverviewDefinitions";
import useAdminOverviewActions from "../../Action/WholeData/useAdminOverviewActions";
import Layout from "../../Layout/Layout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import BaseTable from "../../Components/Common/BaseTable";

// 全体統括者が、個々のユーザーを登録していくページ
export default function AdminOverview({what,type,userDataSets,userKeyInJpn,placeDataSets,placeKeyInJpn}){

  // 定義(変更/削除のフォームなど)
  const { data, setData, post, processing, errors, reset}=useAdminOverviewDefinitions();

  // 動き
  const {onUserChange,onRoleChange,onPlaceChange,onSubmitBtnClick}=useAdminOverviewActions(setData,post);

  return(
    <Layout title={`${what}-${type}`}>
     <div className="h-full min-h-screen bg-lime-200">

        <p>　</p>

        <h1 className="base_h base_h1 min-w-100">全般統括-確認({type})-</h1>

        {/* ユーザーのテーブル */}
        {type!=="営業所" &&
        <div className="mt-10">
          <BaseTable tableTheme="ユーザー" allData={userDataSets} thSets={userKeyInJpn}/>
        </div>
        }

        {/* 営業所のテーブル */}
        {type!=="ユーザー" &&
        <div className="mt-10">
          <BaseTable tableTheme="営業所" allData={placeDataSets} thSets={placeKeyInJpn}/>
        </div>
        }

    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.register_places"  what="営業所の登録"/>
        <BaseLinkLine routeName="whole_data.provision"  what="ユーザーの事前登録"/>
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </div>
    </Layout>
  )
}

