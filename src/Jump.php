<?php
/**
 * Created by PhpStorm.
 * User: wycto
 * Date: 2023/8/15
 * Time: 19:20
 */

namespace wycto\jump;
use think\exception\HttpResponseException;
use think\App;
use think\Response;

trait Jump
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    protected array $jsonMethod = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        $this->jsonMethod = $this->app->config->get('jump.jsonMethod');
    }

    /**
     * 操作成功跳转的快捷方法
     * @param string $msg
     * @param null $data
     * @param array $otherData
     * @param int $code
     * @param array $header
     * @param string|null $url
     * @param int $wait
     */
    protected function success(string $msg = '成功', $data = null, array $otherData=[], int $code=1 , array $header = [], string $url = null, int $wait = 5)
    {
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : (string)$this->app->route->buildUrl($url);
        }

        //判断
        if($url==$_SERVER["HTTP_HOST"] && 'GET'==$_SERVER['REQUEST_METHOD']){
            $url = '';
        }

        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];

        $result = array_merge($result,$otherData);

        $type = $this->getResponseType();
        // 把跳转模板的渲染下沉，这样在 response_send 行为里通过getData()获得的数据是一致性的格式
        if ('view' == $type) {
            $response = Response::create($this->app->config->get('jump.success_tpl'), $type)->assign($result)->header($header);
        } else {
            $response = Response::create($result, $type)->header($header);
        }

        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转的快捷方法
     * @param string $msg
     * @param null $data
     * @param array $otherData
     * @param int $code
     * @param array $header
     * @param string|null $url
     * @param int $wait
     */
    protected function error(string $msg = '失败', $data = null, array $otherData=[], int $code=0, array $header = [], string $url = null, int $wait = 5)
    {
        if (is_null($url)) {
            $url = $this->request->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : (string)$this->app->route->buildUrl($url);
        }

        if($url==$_SERVER["HTTP_HOST"] && 'GET'==$_SERVER['REQUEST_METHOD']){
            $url = '';
        }

        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];

        $result = array_merge($result,$otherData);

        $type = $this->getResponseType();

        if ('view' == $type) {
            $response = Response::create($this->app->config->get('jump.error_tpl'), $type)->assign($result)->header($header);
        } else {
            $response = Response::create($result, $type)->header($header);
        }

        throw new HttpResponseException($response);
    }

    /**
     * 返回封装后的API数据到客户端
     * @param $code
     * @param $msg
     * @param null $data
     * @param array $otherData
     * @param string $type
     * @param array $header
     */
    protected function result($code, $msg, $data=null, array $otherData=[], string $type = '', array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $result = array_merge($result,$otherData);

        $type = $type ?: $this->getResponseType();
        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }

    /**
     * 返回封装后的API数据到客户端
     * @param int $code
     * @param string $msg
     * @param null $data
     * @param array $otherData
     * @param array $header
     */
    protected function json(int $code, string $msg, $data=null, array $otherData=[], array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];

        $result = array_merge($result,$otherData);

        $response = Response::create($result, 'json')->header($header);

        throw new HttpResponseException($response);
    }

    /**
     * 接口成功返回
     * @param string $msg
     * @param null $data
     * @param array $otherData
     * @param array $header
     */
    function json_success(string $msg='成功', $data=null, array $otherData=[], array $header = [],$code=200){
        return $this->json($code,$msg,$data,$otherData,$header);
    }

    /**
     * 接口失败返回
     * @param string $msg
     * @param null $data
     * @param array $otherData
     * @param array $header
     * @param int $code 默认2001 失败
     */
    function json_error(string $msg='失败', $data=null, array $otherData=[], array $header = [], int $code=2001){
        return $this->json($code,$msg,$data,$otherData,$header);
    }

    /**
     * URL重定向
     * @access protected
     * @param string $url 跳转的URL表达式
     * @param integer $code http code
     * @param array $withParam 隐式传参
     * @return void
     */
    protected function redirect(string $url, int $code = 302, array $withParam = [])
    {
        $response = Response::create($url, 'redirect');

        $response->code($code)->with($withParam);

        throw new HttpResponseException($response);
    }

    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType(): string
    {
        return ($this->request->isJson() || $this->request->isAjax() || in_array($this->request->method(),$this->jsonMethod)) ? 'json' : 'view';
    }
}