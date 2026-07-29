// 町丁目データ検索前にユーザーが選択する項目
export default function UserChoisePart({pageMinWidth,pageMaxWidth,theme,subtitile=undefined,children}){
    return (
        <div className={`base_frame w-[50%] mt-2 mb-4 pb-4 px-0 mx-auto box-border border-black border-dashed border-b rounded-sm ${pageMaxWidth} ${pageMinWidth}`}>
            <p className={`text-center font-bold ${subtitile ? "my-1 py-1" : "mt-1 pt-1"}`}>{theme}</p>
            {subtitile && <span className={`inline-block base_frame text-center font-bold mb-1 pb-1 ${pageMaxWidth} ${pageMinWidth}`}>{subtitile}</span> }
        {children}
        </div>
        )
}
