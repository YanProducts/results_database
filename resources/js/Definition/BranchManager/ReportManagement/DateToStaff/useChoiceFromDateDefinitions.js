import { useForm } from "@inertiajs/react";

// 報告書の確認をスタッフから行う場合の定義
export default function useChoiceFromDateDefinitions(){

    // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm([]);

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

    return {data,setData,post,processing, errors,clearErrors, reset, pageMinWidth,pageMaxWidth}
}
