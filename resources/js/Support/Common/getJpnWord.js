// Dom用に日本語を返す
export default function getJpnWord(eng){
    const convertionArray={
        "asc":"昇順",
        "des":"降順",
        "ascOrDes":"昇順or降順",
        // 主に配布データ検索
        "selected_staffs":"該当スタッフ",
        "all_staffs_in_the_places":"営業所全スタッフ",
        "all_staffs":"全スタッフ",
        "project_operator":"案件担当",
        "branch_manager":"営業所担当",
        "field_staff":"現場担当",
        "clerical":"入力担当",
    };
    return convertionArray[eng] ?? eng
}
