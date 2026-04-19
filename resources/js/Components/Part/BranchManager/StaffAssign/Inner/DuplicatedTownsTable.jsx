import BaseTable from "../../../../Common/BaseTable";

// 重複している町のテーブル
export default function DuplicatedTownsTable(duplicatedSets){
    <>
     {/* postデータのみの場合(これ投稿しないでもわかるよね) */}
    <BaseTable/>
    {/* sqlデータと重なっている場合 */}
    <BaseTable/>
    </>
}
