// 認証系統のタイトル
export default function InputPageHeader({what,type,inputWhat="下記",specialMessage="", minWidth="min-w-75", maxWidth="max-w-250"}){
    return(
     <>
        <p>　</p>
        <h1 className={`base_h base_h1 mt-5 mb-5 ${maxWidth} ${minWidth}`}>{what}-{type}-</h1>
        <h2 className={`base_h mb-5 ${maxWidth} ${minWidth}`}>{specialMessage ||inputWhat + "を入力してください"}</h2>
     </>
    );
}
