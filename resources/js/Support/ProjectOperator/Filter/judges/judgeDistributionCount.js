// 部数のフィルター
export default function judgeDistributionCount(projectData, hiddenFilters){

    if(
        (hiddenFilters.includes("50") && projectData <= 50) ||
        (hiddenFilters.includes("100") && projectData > 50 && projectData <= 100) ||
        (hiddenFilters.includes("200") && projectData > 100 && projectData <= 200) ||
        (hiddenFilters.includes("500") && projectData > 200 && projectData <= 500) ||
        (hiddenFilters.includes("800") && projectData > 500 && projectData <= 800) ||
        (hiddenFilters.includes("1000") && projectData > 800 && projectData <= 1000) ||
        (hiddenFilters.includes("3000") && projectData > 1000 && projectData <= 3000) ||
        (hiddenFilters.includes("10000") && projectData > 3000 && projectData <= 10000) ||
        (hiddenFilters.includes("20000") && projectData > 10000 && projectData <= 20000) ||
        (hiddenFilters.includes("50000") && projectData > 20000 && projectData <= 50000) ||
        (hiddenFilters.includes("100000") && projectData > 50000 && projectData <= 100000) ||
        (hiddenFilters.includes("over") && projectData > 100000)
    ){
        return false;
    }

    return true;
}
