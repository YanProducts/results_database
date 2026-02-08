import { useForm } from "@inertiajs/react";

export default function useAuthDefinitions(pageNameSets){

  // 連想配列のキーと連動して値の格納
  const {prefix,what}=pageNameSets;

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    userName:"",
    passWord:"",
    // ページによってはemailとpasswordの確認と新しいパスワードは必要ないが、その際はlaravelでvalidationをしなければ良いだけ
    passWord_confirmation:"",
    email:"",
    newPassWord:"",
    newPassWord_confirmation:"",
  });

  return { data, setData, post, processing, errors, reset,prefix,what}
}
