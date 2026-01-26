import { useForm } from "@inertiajs/react";

export default function useAuthDefinitions({}){
  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    userName:"",
    passWord:"",
    // ページによってはpasswordの確認は必要ないが、その際はlaravelでvalidationをしなければ良いだけ
    passWordConfirmation:"",
  });

  return { data, setData, post, processing, errors, reset}
}