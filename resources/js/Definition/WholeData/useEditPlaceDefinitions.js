import React from "react";

// 営業所の編集における定義
export default function useEditPlaceDefinitions({placeInformation}) {

    // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
        id:placeInformation.id,
    });

    //営業所の名前
    const [placeName,setPlaceName]=React.useState(placeInformation.place_name);

    // 稼働/非稼働
    const [isInWorkChange,setIsInWorkChange]=React.useState(placeInformation.is_active);

    // 色
    const [colors,setColors]=React.useState({
        red:placeInformation.red,green:placeInformation.green,blue:placeInformation.blue
    })

    // バリデーションを表示させるか
    const [validationHidden,setValidationHidden]=React.useState(false); //初期は表示させる(hiddenにさせない)

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];


    return {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,placeName,setPlaceName,colors,setColors,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth};

}
