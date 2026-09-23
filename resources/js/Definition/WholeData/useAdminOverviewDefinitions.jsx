import { useForm } from "@inertiajs/react";
import React from "react";

export default function useAdminOverviewDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({});


  return { data, setData, post, processing, errors, reset}
}
