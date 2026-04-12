// ページ共通するヘッダー部分
export default function BasePageHeader({what,type,subtitle="", pageMinWidth="min-w-75", pageMaxWidth="max-w-250"}){
    return(
     <>
      <p>　</p>

      <h1 className={`base_h base_h1 ${pageMinWidth} ${pageMaxWidth}`}>{what}-{type}-</h1>

        {/* サブタイトルは含まれる時のみ */}
        {subtitle &&
        <div className={`base_frame base_backColor text-center ${pageMinWidth} ${pageMaxWidth}`}><h2 className={`base_h text-2xl mb-10 ${pageMinWidth} ${pageMaxWidth}`}>{subtitle}</h2></div>
        }
     </>
    );
}
