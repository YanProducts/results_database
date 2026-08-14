// 町目数のフィルター
export default function judgeTownCount(projectData, hiddenFilters){

    if(
        (hiddenFilters.includes("5") && projectData <= 5) ||
        (hiddenFilters.includes("10") && projectData > 5 && projectData <= 10) ||
        (hiddenFilters.includes("20") && projectData > 10 && projectData <= 20) ||
        (hiddenFilters.includes("50") && projectData > 20 && projectData <= 50) ||
        (hiddenFilters.includes("100") && projectData > 50 && projectData <= 100) ||
        (hiddenFilters.includes("200") && projectData > 100 && projectData <= 200) ||
        (hiddenFilters.includes("over") && projectData > 200)
    ){
        return false;
    }

    return true;
}
