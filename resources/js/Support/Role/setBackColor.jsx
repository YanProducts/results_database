// roleごとの背景色を返す
export function setBackColor(prefix){
    // それぞれの対応
    const map={
        whole_data:"bg-lime-300",
        project_operator:"bg-sky-300",
        clerical:"bg-yellow-300",
        branch_manager:"bg-orange-300",
        field_staff:"bg-emerald-300"
    };
    return map[prefix];
}
