// projectName:roundNumberの形式から、:より後ろ、つまりroundNumberを除く
export default function getProjectNameWithoutRoundNumberForView(projectNameWithRoundNumber){
    return projectNameWithRoundNumber.substring(0,projectNameWithRoundNumber.indexOf(":"))
}
