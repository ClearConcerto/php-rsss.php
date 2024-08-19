<?php
// RSS源地址列表数组
if (isset($_GET['bbb'])) {
    // 如果是bbb调用，则执行b方案
    //$urls_file = $_GET['urls'];
    $urls_file = isset($_GET['urls']) ? $_GET['urls'] : '1.txt'; // 设置默认文件为1.txt
    $urls_content = file_get_contents($urls_file);
    $rssfeed = explode(PHP_EOL, $urls_content);
    $rssfeed = array_filter($rssfeed); // 过滤掉空行
    $rssfeed = array_map('trim', $rssfeed); // 去除每行的头尾空格
} else {
    // 如果不是bbb调用，则执行a方案
    $rssfeed = isset($_GET['urls']) ? explode(',', $_GET['urls']) : array();
    $rssfeed = array_map('trim', $rssfeed); // 去除每个网址的头尾空格
}

// 设置每个订阅源显示的文章数量
//$max_items_per_feed = 10;
$max_items_per_feed = isset($_GET['max']) ? $_GET['max'] : '10'; // 设置默认10
// 生成RSS XML
$rss_str = '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"><channel><title>My RSS Feed</title><link>http://example.com</link><description>This is my RSS feed</description><language>en-us</language>'; // 用于存储所有订阅源的文章
for ($i = 0; $i < sizeof($rssfeed); $i++) {
    $item_count = 0; // 计数器，用于控制每个订阅源的文章数量
    // 检查rss地址是否可访问
    $headers = @get_headers($rssfeed[$i]);
    if (strpos($headers[0], '200') === false) {
        echo '无法访问 ' . $rssfeed[$i] . PHP_EOL;
        continue;
    }
    // 打开rss地址，并读取
    $rss_content = file_get_contents($rssfeed[$i]);
    // 解析XML
    $xml = simplexml_load_string($rss_content);
    // 检查XML的根元素名称，确定源的格式
    $root_element = $xml->getName();
    if ($root_element == "rss") {
        // RSS格式
        $source = (string)$xml->channel->title; // 获取来源网站
        foreach ($xml->channel->item as $item) {
            // 检查是否已达到最大显示数量
            if ($item_count >= $max_items_per_feed) {
                break;
            }
            $title = (string)$item->title;
            $link = (string)$item->link;
            $description = (string)$item->description; // 获取文章摘要或内容
            $pubDate = (string)$item->pubDate; // 获取更新时间
            // 构造输出字符串
            $rss_str .= "<item>";
            $rss_str .= "<title>" . $source . " - " . "<![CDATA[" . $title . "]]>" . "</title>";
            $rss_str .= "<link><![CDATA[" . $link . "]]></link>";
            $rss_str .= "<description><![CDATA[" . $description . "]]></description>"; // 添加文章摘要或内容
            $rss_str .= "<pubDate>" . $pubDate . "</pubDate>"; // 添加更新时间
            $rss_str .= "</item>";
            // 增加计数器
            $item_count++;
        }
    } elseif ($root_element == "feed") {
        // Atom格式
        $source = (string)$xml->title; // 获取来源网站
        foreach ($xml->entry as $entry) {
            // 检查是否已达到最大显示数量
            if ($item_count >= $max_items_per_feed) {
                break;
            }
            $title = (string)$entry->title;
            $link = (string)$entry->link['href'];
            $description = (string)$entry->content; // 获取文章内容
            $pubDate = (string)$entry->published; // 获取更新时间
            // 构造输出字符串
            $rss_str .= "<item>";
            $rss_str .= "<title>" . $source . " - " . "<![CDATA[" . $title . "]]>" . "</title>";
            $rss_str .= "<link><![CDATA[" . $link . "]]></link>";
            $rss_str .= "<description><![CDATA[" . $description . "]]></description>"; // 添加文章摘要或内容
            $rss_str .= "<pubDate>" . $pubDate . "</pubDate>"; // 添加更新时间
            $rss_str .= "</item>";
            // 增加计数器
            $item_count++;
        }
    }
}
$rss_str .= '</channel></rss>';
// 将输出结果写入rss.xml文件
$outfile = isset($_GET['outfile']) ? $_GET['outfile'] : 'rsss.xml'; // 设置默认文件为rsss.xml
file_put_contents($outfile, $rss_str);
?>