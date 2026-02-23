import { Link } from "@inertiajs/react";
import React from "react";
import { route } from "ziggy-js";

// 各々のテーマのリスト
export default function ThemeLists({routeName,label,routeParams={}}){
  const [themeBackColor,setThemeBackColor]=React.useState("bg-amber-100");
  const handleMouseEnter=()=>{
    setThemeBackColor("bg-sky-400")
  }
  const handleMouseBlur=()=>{
    setThemeBackColor("bg-amber-100")
  }


  return( <div className={`base_frame  min-w-100 border-black bolder-2   mb-5 cursor-pointer ${themeBackColor}`}><p className="my-0 h-10 leading-10  text-center base_backColor font-bold text-lg min-w-100" onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseBlur}  ><Link href={route(routeName,routeParams)}>{label}</Link></p></div>
  )
}
