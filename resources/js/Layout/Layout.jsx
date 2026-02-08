import { Head } from "@inertiajs/react"
import BaseFooter from "../Components/Common/BaseFooter";

// 全体のレイアウト
// childrenにタグで囲われた内容が入る
export default function Layout({children,title}) {
  return (
    <>
      <Head title={title ?? "データ共有"} />
        {children}
      <BaseFooter></BaseFooter>
    </>
  )
}
