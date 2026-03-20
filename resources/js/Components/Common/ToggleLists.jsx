//回答が２択の場合のチェックボックスのUI
// id_numberがhtmlForの識別子、contentsが表示する内容をリスト化したもの、formListsがそのformのリスト

// contentsには「id」と「nameForUI」が両方セットされている必要あり
export default function ToggleLists({contents,formLists,onToggleListsChange,labelWhenTrue,labelWhenFalse}){
    return(
        <div className="mt-2 px-5 py-1 text-center   base_frame min-w-100">
            {Object.values(contents).map(function(content,index){
                const id=content.id;
                return(
                <div key={index} className="flex justify-around base_frame min-w-75">
                    <span className="bg-lime-200 w-1/2 mx-0 border border-black">{content.nameForUI}</span>
                    <span className={` border-black border w-1/3 mx-0 ${formLists.includes(id) ? "bg-amber-200 font-bold" : "bg-white"}`}>
                    {formLists.includes(id) ? labelWhenTrue :  labelWhenFalse}
                    </span>
                    {/* formListsに該当idが含まれているかどうか */}
                    <input className="hidden" id={`${id}`} type="checkbox" checked={formLists?.includes(id)} onChange={onToggleListsChange} value={`${id}`}/>
                    <label className="inline-block cursor-pointer border-black border w-1/6 bg-gray-200" htmlFor={`${id}`}>変更</label>
                </div>
                )
            }
            )}
        </div>
    )
}
