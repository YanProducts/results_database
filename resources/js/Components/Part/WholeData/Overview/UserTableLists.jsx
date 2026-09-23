import BaseTable from "../../../Common/BaseTable"

// ユーザーのテーブル一覧
export default function UserTableLists({userDataSets,userKeyInJpn,onChangeUserDecideClick}){

      // 案件担当と入力担当用のthのキー。２度使うから前もって
      const thKeyWithoutPlaceAndStaffName=Object.fromEntries(Object.entries(userKeyInJpn).filter(([key,value])=>!key.includes(["place_name","staff_name"])))

    return(
          <>
            <div className="mt-10">
            <BaseTable tableTheme="案件担当" allData={userDataSets.filter(eachUser=>eachUser.role=="project_operator")} thSets={thKeyWithoutPlaceAndStaffName} editFunc={onChangeUserDecideClick} editFuncParam={"project_operator"} editFuncKey={"id"}/>
            </div>

            <div className="mt-10">
            <BaseTable tableTheme="営業所担当" allData={userDataSets.filter(eachUser=>eachUser.role=="branch_manager")} thSets={Object.fromEntries(Object.entries(userKeyInJpn).filter(([key,value])=>key!=="staff_name"))}  editFunc={onChangeUserDecideClick} editFuncParam={"branch_manager"} editFuncKey={"id"}/>
            </div>

            <div className="mt-10">
            <BaseTable tableTheme="現場担当" allData={userDataSets.filter(eachUser=>eachUser.role=="field_staff")} thSets={userKeyInJpn}  editFunc={onChangeUserDecideClick} editFuncParam={"field_staff"} editFuncKey={"id"}/>
            </div>

            <div className="mt-10">
            <BaseTable tableTheme="入力担当" allData={userDataSets.filter(eachUser=>eachUser.role=="clerical")} thSets={thKeyWithoutPlaceAndStaffName}  editFunc={onChangeUserDecideClick} editFuncParam={"clerical"} editFuncKey={"id"}/>
            </div>
          </>

    )
}
