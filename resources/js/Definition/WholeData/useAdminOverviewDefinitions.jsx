import { useForm } from "@inertiajs/react";
import React from "react";

export default function useAdminOverviewDefinitions({}){

 // ページの横幅
  const [pageMinWidth,pageMaxWidth]=["min-w-150","max-w-300"];

// sortのセット
const [allPrioritySets,setAllPrioritySets]=React.useState({
    "field_staff":[
        {"priority":"is_active","ascOrDes":"des"},
        {"priority":"place_id","ascOrDes":"asc"},
        {"priority":"id","ascOrDes":"asc"},
    ],
    "branch_manager":[
        {"priority":"is_active","ascOrDes":"des"},
        {"priority":"place_id","ascOrDes":"asc"},
        {"priority":"id","ascOrDes":"asc"},
    ],
    "project_operator":[
        {"priority":"is_active","ascOrDes":"des"},
        {"priority":"id","ascOrDes":"asc"},
    ],
    "clerical":[
        {"priority":"is_active","ascOrDes":"des"},
        {"priority":"id","ascOrDes":"asc"},
    ],
})

  return {allPrioritySets,setAllPrioritySets,pageMaxWidth,pageMinWidth}
}
