//回答が２択の場合のチェックボックスのUI
// id_numberがhtmlForの識別子、contentsが表示する内容をリスト化したもの、formListsがそのformのリスト

// contentsには「id」と「nameForUI」が両方セットされている必要あり
export default function ToggleLists(contents,formLists,onToggleListsChange,labelWhenTrue,labelWhenFalse){
    return(
        <div>
            {contents.map(function(content,index){
                const id=content.id;
                return(
                <div key={index} className="flex">
                    <span>{content.nameForUI}</span>
                    <span className={` border-black rounded-sm ${formLists.includes(id) ? "bg-amber-200 font-bold" : "bg-gray-400"}`}>
                    {formLists.includes(id) ? labelWhenTrue :  labelWhenFalse}
                    </span>
                    {/* formListsに該当idが含まれているかどうか */}
                    <input className="hidden" id={`${id}`} type="checkbox" checked={formLists.includes(id)} onClick={onToggleListsChange} value={`${id}`}/>
                    <label className="inline-block cursor-pointer border-black rounded-sm bg-white" htmlFor={`${id}`}>変更！</label>
                </div>
                )
            }
            )}
        </div>
    )
}
