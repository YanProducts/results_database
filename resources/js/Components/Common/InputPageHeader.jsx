import ViewUserName from "./ViewUserName";
// 認証系統のタイトル
export default function InputPageHeader({what,type,inputWhat="下記",specialMessage="", minWidth="min-w-75", maxWidth="max-w-250",needUserName=false,userName="",nameBackColor="bg-white", nameColor="bg-black"}){
    return(
     <>
        <p>　</p>
        <h1 className={`base_h base_h1 mt-5 ${needUserName ? "mb-2" :"mb-5"} ${maxWidth} ${minWidth}`}>{what}-{type}-</h1>

        {/* スタッフ名の表示 */}
        {needUserName ?
        <ViewUserName {...{pageMinWidth:minWidth,pageMaxWidth:maxWidth,userName:userName,nameBackColor:nameBackColor, nameColor:nameColor}}/>
        :
        null
        }

        <h2 className={`base_h mb-5 ${maxWidth} ${minWidth}`}>{specialMessage ||inputWhat + "を入力してください"}</h2>
     </>
    );
}
