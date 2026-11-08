//回答が２択の場合のチェックボックスのUI
// 表示非表示のタグを作らず、名前のみが羅列する形にする
// id_numberがhtmlForの識別子、contentsが表示する内容をリスト化したもの、formListsがそのformのリスト

// contentsには「id」と「nameForUI」が両方セットされている必要あり
export default function FlatToggleLists({contents,formLists,onToggleListsChange ,width="",pageMaxWidth="",pageMinWidth="",prefixId="",formNotNumberFlag=false}){
    return(
        <div className={`mt-2 py-1 text-center base_frame ${pageMinWidth} ${pageMaxWidth}`}>
            {Object.values(contents).map(function(content,index){
                const id=content.id;
                const isChecked = formLists.map(eachForm => formNotNumberFlag ? eachForm : Number(eachForm)).includes(id);
                return(
                <div key={index} className={`flex justify-around base_frame ${width} ${pageMinWidth} ${pageMaxWidth}`}>
                    <label className={`${isChecked ? "bg-lime-200" : "bg-gray-200" } w-[80%] my-1 mx-0 border cursor-pointer border-black`} htmlFor={`${prefixId} + ${id}`}>{content.nameForUI}</label>
                    {/* formListsに該当idが含まれているかどうか */}
                    <input className="hidden" id={`${prefixId} + ${id}`} type="checkbox" checked={isChecked} onChange={onToggleListsChange} value={`${id}`}/>
                </div>
                )
            }
            )}
        </div>
    )
}
