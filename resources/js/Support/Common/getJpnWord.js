// Dom用に日本語を返す
export default function getJpnWord(eng){
    const convertionArray={
        "asc":"昇順",
        "des":"降順",
        "ascOrDes":"昇順or降順",

    };
    return convertionArray[eng] ?? eng
}
