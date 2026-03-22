import BaseButton from "./BaseButton";

// 入力系統のinput
export default function InputFiles({name,minWidth="min-w-72.5", maxWitdh="max-w-140",onChange,prefix,prefixPercent="w-[40%]", labelPercent="w-[60%]", attributes={},data,onFileDeleteClick}){
  return(
    <>
        <div className={`flex items-center base_frame mx-auto my-3 ${minWidth} ${maxWitdh}`}>
        <span className={`inline-block w-[30%] min-w-32 text-right ${prefixPercent}`}>{prefix}</span>
        <input className="hidden" id="file_input" type="file" name={name} onChange={onChange} {...attributes}/>
        {/* htmlForを設定することで、クリックされ時にinputが変化して活性化する*/}
        <label htmlFor="file_input" className={`bg-gray-200 inline-block min-w-35 border-black border rounded-sm pl-1 cursor-pointer ${labelPercent}`}>
                ファイルを選択してください
        </label>
        </div>
        {/* 現在取得中のファイル表示 */}
        <div className={`flex items-center base_frame mx-auto my-3 ${minWidth} ${maxWitdh}`}>
            <div className={prefixPercent}></div>
            <div className={`min-w-40 text-center mt-0 mb-3  text-sm mx-auto ${labelPercent}`}>{data.fileSets.map((file,index)=>
                <div key={index} className="flex items-center justify-center border border-black">
                    {/* ファイル名 */}
                  <span className=" base_backColor w-full">{file.name}</span>
                  {/* 削除ボタン */}
                  <div className="w-[10%] mx-auto bg-gray-300 cursor-pointer text-center border border-black mr-0"
                    onClick={()=>onFileDeleteClick(index)}
                  >x</div>
                </div>
                )}
            </div>
        </div>
    </>
  )
}
