// css用にユーザーの情報を編集する際の最後の項目と最初の項目を取得
export default function getTopAndLastItemInEditUserForm({role,registered}){

    // 最初の項目
    const topItem=["field_staff","branch_manager"].includes(role) ? "place" : (registered ? "registered" : "isActive");

    return {topItem}
}
