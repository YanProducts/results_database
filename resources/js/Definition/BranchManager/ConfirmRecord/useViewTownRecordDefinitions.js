// 町目結果検索
export default function useViewTownRecordDefinitions({serchStaffs}){
        // スタッフの名前リスト
    const staffNameLists=(serchStaffs==null ||serchStaffs==undefined) ? [] : Object.values(serchStaffs);

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-200"];

    return {staffNameLists,pageMinWidth,pageMaxWidth}
}
