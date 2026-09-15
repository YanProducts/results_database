// バリデーション以外の注意などを一元化表示
export default function ViewAttentions({message,mt="",mb="",minWidth="",maxWidth="", plusCss=""}){
  return(
    <div className={`${mb} ${mt} ${minWidth} ${maxWidth} base_error ${plusCss}`}>
        <p>{message}</p>
    </div>
  )
}
