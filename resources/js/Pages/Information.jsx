import Layout from "../Layout/Layout";
import { Link } from "@inertiajs/react";
import { route } from "ziggy-js";
import BasePageHeader from "../Components/Common/BasePageHeader";

// お知らせページ
export default function Information({message,linkRouteName="top_page",routeParams={},linkPageInJpn="トップ"}){
    return(
    <Layout title="お知らせ">
    <div className="custom_body bg-green-600">
      <BasePageHeader what="データ共有" type="お知らせ"/>
      <div className="base_frame"><p className="base_information">{message}</p></div>
      <div className="base_link"><p className="base_link_p"><Link href={route(linkRouteName,routeParams)}>{linkPageInJpn}へ</Link></p></div>
    </div>
    </Layout>
  )

}
