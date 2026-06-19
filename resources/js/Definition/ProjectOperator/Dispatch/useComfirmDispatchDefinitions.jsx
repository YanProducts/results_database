import { useForm } from "@inertiajs/react";

export default function useConfirmDispatchDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    // 重複候補のプロジェクトのリスト
    // 新しい案件の場合のみ、projectのidを記載
    "newProjects":[],
    //重複候補の町目は自動的に変更(「やり直す」以外は)
  });

  const [pageMinWidth,pageMaxWidth]=["min-w-150","max-w-250"]

  return { data, setData, post, processing, errors, reset,pageMinWidth,pageMaxWidth}
}
