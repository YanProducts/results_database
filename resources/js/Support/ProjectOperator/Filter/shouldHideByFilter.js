import judgeDistributionCount from "./judges/judgeDistributionCount";
import judgeTownCount from "./judges/judgeTownCount";
import judgeProjectName from "./judges/judgeProjectName";
import judgeDate from "./judges/judgeDate";
import { all } from "axios";

// フィルターするかどうか
export default function shouldHideByFilter({projectSets,allHiddenLists}){

    if(!judgeProjectName(projectSets.project_name,allHiddenLists.project_name) || !judgeDate(projectSets.start_date,allHiddenLists.start_date) || !judgeDate(projectSets.end_date,allHiddenLists.end_date) || !judgeTownCount(projectSets.town_count,allHiddenLists.town_count) || !judgeTownCount(projectSets.finished_town_count,allHiddenLists.finished_town_count) || !judgeDistributionCount(projectSets.distribution_plan_count,allHiddenLists.distribution_plan_count) || !judgeDistributionCount(projectSets.finished_distribution_count,allHiddenLists.finished_distribution_count)){
        return false;
    }

return true;

}
