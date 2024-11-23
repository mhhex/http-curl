# http-curl

适用于 thinkphp >= 6.0.0 的网络操作类库

## 主要特性

* 轻量级
* 支持链写法
* 灵活请求配置
* 可选安全配置
* 灵活响应处理

## 安装

~~~php
composer require mhhex/http-curl
~~~

## 使用文档

### 声明

~~~php
use mhhex\HttpCurl;
~~~

### 示例

~~~php
// 创建HttpCurl类的实例
$httpCurl = new HttpCurl();

// 使用链式写法依次设置请求的URL、数据、方法、请求头以及Cookie等信息，并发送请求
$result = $httpCurl
    ->setUrl('https://api.example.com/users') // 设置请求的URL
    ->setData(['name' => 'John', 'age' => 30]) // 设置要发送的数据（这里模拟发送用户信息）
    ->setMethod('POST') // 设置请求方法为POST
    ->setHeader('Content-Type', 'application/json') // 设置请求头，表明发送的数据是JSON格式
    ->setHeader('Authorization', 'Bearer your_token_here') // 设置授权头，这里替换为真实的授权token
    ->setCookie('prev_session_id=12345') // 设置请求的Cookie信息（示例Cookie，可按需替换）
    ->send(); // 发送请求

// 根据send方法的返回类型（可能是数组或者原始字符串）进行相应处理
if (is_array($result)) {
    // 如果返回的是数组，通常意味着是JSON解析后的结果，这里可以进行具体业务逻辑操作，比如提取数据等
    echo "返回的是JSON解析后的数组，以下是部分数据示例：<br>";
    echo "用户名: ". $result['name']. "<br>";
    echo "年龄: ". $result['age'];
} else {
    // 如果返回的是原始字符串，可能是其他格式的数据，比如HTML等，可按需输出查看等
    echo "返回的是原始字符串内容：<br>". $result;
}
~~~

## 版权信息

http-curl遵循Apache2开源协议发布，并提供免费使用。
