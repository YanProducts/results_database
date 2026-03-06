<?php
// ProjectModelに関するサポート関数

namespace App\Support\CommonModelHelpers;

use App\Models\Project;

class ProjectHelpers{
    // 案件名からプロジェクト名を返還
    public static function get_id_from_name($project_name){
        Project::where("project_name",$project_name)->value("id");
    }

}
