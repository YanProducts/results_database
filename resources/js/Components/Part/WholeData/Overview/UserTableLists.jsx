import sortDataByMultipleKeys from "../../../../Support/Common/sortDataByMultipleKeys"
import BaseTable from "../../../Common/BaseTable"

// ユーザーのテーブル一覧
export default function UserTableLists({userDataSets,userKeyInJpn,onChangeUserDecideClick,allPrioritySets,pageMaxWidth,pageMinWidth}){

    // 案件担当と入力担当用のthのキー
    const thKeyWithoutPlaceAndStaffName = Object.fromEntries(
        Object.entries(userKeyInJpn)
            .filter(([key]) => !["place_name", "staff_name"].includes(key))
    );

    // BaseTableの共通パラメータ
    const baseTableParams = {
        plusTrCss: "cursor-pointer hover:border-4 border-sky-400",
        plusTrCssFunc: (eachData) => !eachData.is_active && "bg-gray-300",
        editFunc: onChangeUserDecideClick,
        editFuncKey: "id",
    };

    return (
        <>
            {/* 案件担当 */}
            <div className={`mt-10 ${pageMaxWidth} ${pageMinWidth}`}>
                <BaseTable
                    {...baseTableParams}
                    tableTheme="案件担当"
                    allData={sortDataByMultipleKeys({
                        sourceData: userDataSets.filter(eachUser => eachUser.role === "project_operator"),
                        prioritySets: allPrioritySets?.project_operator || []
                    })}
                    thSets={thKeyWithoutPlaceAndStaffName}
                    editFuncParam="project_operator"
                />
            </div>

            {/* 営業所担当 */}
            <div className={`mt-10 ${pageMaxWidth} ${pageMinWidth}`}>
                <BaseTable
                    {...baseTableParams}
                    tableTheme="営業所担当"
                    allData={sortDataByMultipleKeys({
                        sourceData: userDataSets.filter(eachUser => eachUser.role === "branch_manager"),
                        prioritySets: allPrioritySets?.branch_manager || []
                    })}
                    thSets={Object.fromEntries(
                        Object.entries(userKeyInJpn)
                            .filter(([key,value]) => key !== "staff_name")
                    )}
                    editFuncParam="branch_manager"
                />
            </div>

            {/* 現場担当 */}
            <div className={`mt-10 ${pageMaxWidth} ${pageMinWidth}`}>
                <BaseTable
                    {...baseTableParams}
                    tableTheme="現場担当"
                    allData={sortDataByMultipleKeys({
                        sourceData: userDataSets.filter(eachUser => eachUser.role === "field_staff"),
                        prioritySets: allPrioritySets?.field_staff || []
                    })}
                    thSets={userKeyInJpn}
                    editFuncParam="field_staff"
                />
            </div>

            {/* 入力担当 */}
            <div className={`mt-10 ${pageMaxWidth} ${pageMinWidth}`}>
                <BaseTable
                    {...baseTableParams}
                    tableTheme="入力担当"
                    allData={sortDataByMultipleKeys({
                        sourceData: userDataSets.filter(eachUser => eachUser.role === "clerical"),
                        prioritySets: allPrioritySets?.clerical || []
                    })}
                    thSets={thKeyWithoutPlaceAndStaffName}
                    editFuncParam="clerical"
                />
            </div>
        </>
    )
}
