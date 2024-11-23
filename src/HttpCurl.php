<?php

namespace mhhex;

class HttpCurl
{
    // 请求的URL
    private string $url = '';
    // 要发送的数据
    private array $data = [];
    // 默认 GET 请求方法
    private string $method = 'GET';
    // 请求头信息
    private array $headers = [];
    // 请求 Cookie 信息
    private string $cookie = '';
    // 是否验证SSL证书的对等方
    private bool $sslVerifyPeer = false;
    // 是否验证SSL证书的主机
    private bool $sslVerifyHost = false;
    // 是否包含响应头
    private bool $includeHeader = false;
    // 是否返回数据而不是直接输出
    private bool $returnTransfer = true;
    // 是否自动跟随重定向
    private bool $followLocation = true;

    /**
     * 设置请求的URL
     *
     * @param string $url 要请求的URL
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 设置要发送的数据
     *
     * @param array $data 要发送的数据
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 设置请求方法
     *
     * @param string $method 请求方法，可选 'POST' 或 'GET'
     * @return $this
     */
    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 设置请求头信息
     *
     * @param string $name 请求头字段名，如 'User-Agent'
     * @param string $value 请求头字段对应的值
     * @return $this
     */
    public function setHeader(string $name, string $value): static
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * 设置请求Cookie信息
     *
     * @param string $cookie Cookie信息字符串，格式如 "name1=value1; name2=value2"
     * @return $this
     */
    public function setCookie(string $cookie): static
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * 设置是否验证SSL证书的对等方
     *
     * @param bool $sslVerifyPeer 是否验证SSL证书的对等方
     * @return $this
     */
    public function setSslVerifyPeer(bool $sslVerifyPeer): static
    {
        $this->sslVerifyPeer = $sslVerifyPeer;
        return $this;
    }

    /**
     * 设置是否验证SSL证书的主机
     *
     * @param bool $sslVerifyHost 是否验证SSL证书的主机
     * @return $this
     */
    public function setSslVerifyHost(bool $sslVerifyHost): static
    {
        $this->sslVerifyHost = $sslVerifyHost;
        return $this;
    }

    /**
     * 设置是否包含响应头
     *
     * @param bool $includeHeader 是否包含响应头
     * @return $this
     */
    public function setIncludeHeader(bool $includeHeader): static
    {
        $this->includeHeader = $includeHeader;
        return $this;
    }

    /**
     * 设置是否返回数据而不是直接输出
     *
     * @param bool $returnTransfer 是否返回数据而不是直接输出
     * @return $this
     */
    public function setReturnTransfer(bool $returnTransfer): static
    {
        $this->returnTransfer = $returnTransfer;
        return $this;
    }

    /**
     * 设置是否自动跟随重定向
     *
     * @param bool $followLocation 是否自动跟随重定向
     * @return $this
     */
    public function setFollowLocation(bool $followLocation): static
    {
        $this->followLocation = $followLocation;
        return $this;
    }

    /**
     * 发送请求
     *
     * @param string $url 要请求的URL（可选，若提供则覆盖之前设置的）
     * @param array $data 要发送的数据（可选，若提供则覆盖之前设置的）
     * @param string $method 请求方法（可选，若提供则覆盖之前设置的）
     * @return array|string
     */
    public function send(string $url = '', array $data = [], string $method = ''): array|string
    {
        if ($url) {
            $this->url = $url;
        }

        if ($data) {
            $this->data = $data;
        }

        if ($method) {
            $this->method = $method;
        }

        // 初始化 cURL 会话
        $curl = curl_init();

        // 对于 GET 请求，如果有数据，将其拼接到 URL 上
        if ($this->method === 'GET' && !empty($this->data)) {
            $this->url .= '?' . http_build_query($this->data);
        }

        // 设置要请求的 URL
        curl_setopt($curl, CURLOPT_URL, $this->url);

        // 对于 POST 请求，进行相应设置
        if ($this->method === 'POST') {
            // 设置请求方法为 POST
            curl_setopt($curl, CURLOPT_POST, 1);
            // 设置 POST 数据
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
        }

        // 循环设置请求头
        foreach ($this->headers as $name => $value) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, [$name . ': ' . $value]);
        }

        // 设置 Cookie 信息
        curl_setopt($curl, CURLOPT_COOKIE, $this->cookie);
        // 不验证 SSL 证书的对等方
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
        // 不验证 SSL 证书的主机
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->sslVerifyHost);
        // 不包含响应头
        curl_setopt($curl, CURLOPT_HEADER, $this->includeHeader);
        // 设置返回数据而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $this->returnTransfer);
        // 设置自动跟随重定向
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $this->followLocation);

        // 设置处理响应头的回调函数
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$receivedCookies) {
            if (strpos($header, 'Set-Cookie:') === 0) {
                $cookieParts = explode(';', substr($header, 12));
                $receivedCookies[] = trim($cookieParts[0]);
            }
            return strlen($header);
        });

        // 执行 cURL 会话并获取结果
        $result = curl_exec($curl);

        // 获取响应头
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

        // 关闭 cURL 会话
        curl_close($curl);

        // 设置请求 Cookie 信息字符串
        $this->cookie = empty($receivedCookies) ? '' : implode(';', $receivedCookies);

        return str_starts_with($contentType, "application/json") ? json_decode($result, true) : $result;
    }
}