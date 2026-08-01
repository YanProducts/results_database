// テキストエリアの基本形
// 貼り付ける場合など
export default function BaseTextArea({textName,textData,changeFunc,pageMinWidth,pageMaxWidth,needTitle=false,textAreaTitle="",placeholder=null}){
    return(
        <>
         {needTitle &&
           <p className={`base_frame base_backColor text-center ${pageMinWidth} ${pageMaxWidth}`}>{textAreaTitle}</p>
         }
         <textarea className={`border-black border rounded-sm base_frame base_backColor my-2 p-1 ${pageMaxWidth} ${pageMinWidth}`} name={textName} onChange={changeFunc} value={textData}  rows={20} placeholder={placeholder ||  ""}/>
        </>
    )
}
