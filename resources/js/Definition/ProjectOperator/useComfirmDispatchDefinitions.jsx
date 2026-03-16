import { useForm } from "@inertiajs/react";

export default function useConfirmDispatchDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    // 重複候補のプロジェクトのリスト
    // 新しい案件の場合のみ、projectのidを記載
    "newProject":[],
    //重複候補の町目は自動的に変更(「やり直す」以外は)
  });

  return { data, setData, post, processing, errors, reset}
}
