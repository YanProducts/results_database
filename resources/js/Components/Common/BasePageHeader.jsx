import ViewUserName from "./ViewUserName";

// ページ共通するヘッダー部分
export default function BasePageHeader({what,type,subtitle="", pageMinWidth="min-w-75", pageMaxWidth="max-w-250",mb="mb-10",needUserName=false,userName="",nameBackColor="bg-white", nameColor="bg-black"}){
    return(
     <>
      <p>　</p>

      <h1 className={`base_h base_h1 ${pageMinWidth} ${pageMaxWidth}`}>{what}-{type}-</h1>

        {/* スタッフ名の表示 */}
    {needUserName ?
        <ViewUserName {...{userName:userName,nameBackColor:nameBackColor, nameColor:nameColor,pageMinWidth,pageMaxWidth}}/>
        :
        null
     }

        {/* サブタイトルは含まれる時のみ */}
        {subtitle &&
        <div className={`base_frame base_backColor text-center ${pageMinWidth} ${pageMaxWidth}`}><h2 className={`base_h text-2xl whitespace-pre-wrap ${mb} ${pageMinWidth} ${pageMaxWidth}`}>{subtitle}</h2></div>
        }
     </>
    );
}
