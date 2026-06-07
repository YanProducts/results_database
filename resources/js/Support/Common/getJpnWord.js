// Dom用に日本語を返す
export default function getJpnWord(eng){
    const convertionArray={
        "asc":"昇潤",
        "des":"降潤",
        "ascOrDes":"昇順or降順"
    };
    return convertionArray[eng] ?? eng
}
