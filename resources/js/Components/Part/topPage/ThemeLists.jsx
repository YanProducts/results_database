import { Link } from "@inertiajs/react";
import React from "react";

// 各々のテーマのリスト
export default function ThemeLists({href,label}){
  const [themeBackColor,setThemeBackColor]=React.useState("bg-amber-100");
  const handleMouseEnter=()=>{
    setThemeBackColor("bg-sky-400")
  }
  const handleMouseBlur=()=>{
    setThemeBackColor("bg-amber-100")
  }

  return(
    <div className={`base_frame border-black bolder-2   mb-5 cursor-pointer ${themeBackColor}`}><p className="my-0 h-10 leading-10  text-center base_backColor font-bold text-lg" onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseBlur}  ><Link href={href}>{label}</Link></p></div>
  )
}