// 認証系統のタイトル
export default function InputPageHeader({what,type,inputWhat}){
    return(
     <>
        <p>　</p>
        <h1 className="base_h base_h1 mt-10 mb-5">{what}-{type}-</h1>
        <h2 className="base_h mb-5">{inputWhat}を入力してください</h2>
     </>
    );
}
