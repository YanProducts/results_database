import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import SelectPartsForViewChange from "../../../Components/Common/SelectPartsForViewChange";
import InputPageHeader from "../../../Components/Common/InputPageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../Components/Common/BaseButton";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useAssignProjectToStaffDefinitions from "../../../Definition/BranchManager/ProjectAssingment/useAssignProjectToStaffDefinitions";
import useAssignProjectToStaffActions from "../../../Action/BranchManager/ProjectAssignment/useAssingProjectToStaffActions";
import RadioButton from "../../../Components/Common/RadioButton";
import MapLists from "../../../Components/Part/BranchManager/StaffAssign/MapLists";
import TownLists from "../../../Components/Part/BranchManager/StaffAssign/TownLists";

// 案件を営業所担当に送信
export default function AssingProjectToStaff({prefix,what,type,projectsAndTowns,dateSets,staffs}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset, assignPlan,setAssignPlan,selectedDate,setSelectedDate,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,selectedMapNumber,setSelectedMapNumber,townAssign,setTownAssign}=useAssignProjectToStaffDefinitions();

  // 動き
  const {onSubmitBtnClick,onSelectedDateChange,onSelectedMainProjectChange,onChangeMapOrTown,handleAssignChangeInMaps,handleAssignChangeInTowns}=useAssignProjectToStaffActions(dateSets,projectsAndTowns,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,setNeedNumber,setSelectedMapNumber,setSelectedDate);


  return(
    <Layout title={`${what}-${type}`}>
    <RoleLayout prefix={prefix}>

    {/* タイトル */}
    <InputPageHeader what={what} type={type} inputWhat="日付"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
             <div className="base_frame min-w-100 max-w-120 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets}/>

                {/* メイン案件名(クリックすれば「MapNo選択⇨必要なら修正」or「町目リストから直接」の項目開く) */}
                <SelectPartsForViewChange onChange={onSelectedMainProjectChange} prefix={"メイン案件名："} keyValueSets={Object.fromEntries(Object.keys(projectsAndTowns).map(project=>[project,project]))}/>

                {/* 案件ナンバーと町目リストのどちらから選ぶか */}
                <RadioButton onChange={onChangeMapOrTown}  minWidth="min-w-80" maxWitdh="w-[100%]"
                contentsSets={[{"prefix":"地図番号から選ぶ","value":"mapNumber"},{"prefix":"町目リストから選ぶ","value":"townList"}]} radioName="mapOrTown" stateForSelected={needNumber}/>

              </div>

                {/* 町目リストとスタッフ */}
                <TownLists projectsAndTowns={projectsAndTowns} selectedMainProject={selectedMainProject} staffs={staffs} handleAssignChangeInTowns={handleAssignChangeInTowns}/>


                {/* MapNoとスタッフ */}
                <MapLists projectsAndTowns={projectsAndTowns} selectedMainProject={selectedMainProject} staffs={staffs} handleAssignChangeInMaps={handleAssignChangeInMaps}/>


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

