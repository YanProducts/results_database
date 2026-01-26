import Layout from "../Layout/Layout";
import { Link } from "@inertiajs/react";

// エラー
export default function Information({message}){
  return(
    <Layout title="お知らせ">
      <div className="base_h"><h1 className="base_h1">お知らせ</h1></div>
      <div className="base_frame"><p className="base_infomation">{message}</p></div>
      <div><Link href="topPage">トップへ</Link></div>
    </Layout>
  )

}