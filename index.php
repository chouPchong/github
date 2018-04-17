<?php
header("Content-Type: text");

include 'weather.php';
include 'baidumap.php';
/**
 * 功能: 验证来自微信服务器签名逻辑
 */
// 定义令牌: 必须和微信公众平台token一模一样
define("TOKEN", "weixin");
define("APP_ID", "wx3f51800720810ab5");
define("APP_SECRET", "c94814f967e58c7bb047f761acba1efd");

// 1.实例化
$wechatObj = new WechatCallbackAPI();

// 2.调用方法
if (isset($_GET['echostr'])) {
	//验证
	$wechatObj->valid();
} else {
	//响应用户
	$wechatObj->responseMsg();
}

class WechatCallbackAPI {

	/**
	 * 功能: 判断并验证签名, vaild 有效的;
	 * @return echoStr字符串
	 */
	public function valid() {
		// 1.读取echostr字段的值
		$echoStr = $_GET["echostr"];
		// 2.调用私有方法, 方法返回值验证成功还是失败
		if ($this->checkSignature()) {
			echo $echoStr;
			exit;
		}
	}

	/**
	 * 功能: 通过微信服务器发送给开发者服务器的字段, 判断是否来自于微信服务器
	 * @return Bool true/false
	 */
	private function checkSignature() {
		//  1）将token、timestamp、nonce三个参数进行字典序排序
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = TOKEN;

		$tmpArray = array($token, $timestamp, $nonce);
		sort($tmpArray);

		// 2）将三个参数字符串拼接成一个字符串进行sha1加密
		$tmpStr = implode($tmpArray);
		$tmpStr = sha1($tmpStr);

		// 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 功能:响应信息
	 *
	 * @return $result 输出响应xml字符串
	 */
	public function responseMsg() {
		//获取post传输到的XML
		// 1. PHP7.0以上
		$postStr = file_get_contents("php://input");
		// 2. PHP7.0以下
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//file_put_contents('postStr.txt', $postStr);
		//把结果转化成对象
		if (!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, "SimpleXMLElement", LIBXML_NOCDATA);
			file_put_contents('postObj.txt', $postObj);
			//判断类型,选择方法回复
			switch ($postObj->MsgType) {
			case 'text':
				$result = $this->receiveText($postObj);
				break;
			case 'image':
				$result = $this->receiveImage($postObj);
				break;
			case 'event':
				$result = $this->receiveEvent($postObj);
				break;
			case 'location':
				$result = $this->receiveLocation($postObj);
				break;
			default:
				$content = 'TODO: 其他未处理消息类型';
				$result = $this->transmitText($postObj, $content);
				break;
			}

			echo $result;
		} else {
			echo '';
			exit;
		}
	}

	/**
	 * 功能: 处理接收的文本消息
	 *
	 * @param SimpleXMLElement $postObj
	 * @return String $result
	 */
	private function receiveText($postObj) {
		$keywords = trim($postObj->Content, '');
		if ($keywords == '图文') {
			$content = array();
			$content[] = array('Title' => '标题1', 'Description' => '描述1', 'PicUrl' => 'http://www.renxinjing.cn/images/128.png', 'Url' => 'http://m.baidu.com');
			$content[] = array('Title' => '标题2', 'Description' => '描述2', 'PicUrl' => 'http://www.renxinjing.cn/images/48-12.png', 'Url' => 'http://m.dingping.com');
			$content[] = array('Title' => '标题3', 'Description' => '描述3', 'PicUrl' => 'http://www.renxinjing.cn/images/48-13.png', 'Url' => 'http://www.apple.com.cn');
			$content[] = array('Title' => '标题4', 'Description' => '描述4', 'PicUrl' => 'http://www.renxinjing.cn/images/48-14.png', 'Url' => 'http://www.github.com');

		} elseif (strstr($keywords, '天气') == '天气') {
			$city = str_replace('天气', '', $keywords);
			$content = getWeatherInfo($city);

		} elseif (strstr($keywords, '附近') == '附近') {
			$entity = str_replace('附近', '', $keywords);
			$content = getNearByEntity($entity, $postObj->FromUserName);

		} else {
			$content = '你发送的是文本消息:' . $postObj->Content;
		}

		if (is_array($content)) {
			$result = $this->transmitNews($postObj, $content);
		} else {
			$result = $this->transmitText($postObj, $content);
		}

		return $result;
	}

	/**
	 * 功能: 处理接收的图片消息
	 *
	 * @param SimpleXMLElement $postObj
	 * @return String $result
	 */
	private function receiveImage($postObj) {
		$content = '你发送的是图片消息,MediaId是：' . $postObj->MediaId;
		$result = $this->transmitText($postObj, $content);
		return $result;
	}

	/**
	 * 功能: 接收事件类型(6种事件类型)
	 *
	 * @param $postObj
	 * @return String
	 */
	private function receiveEvent($postObj) {
		$eventType = $postObj->Event;
		switch ($eventType) {
		case 'subscribe': //关注事件
			$content = $this->handleSubscribe($postObj);
			break;
		case 'SCAN':
			$content = '针对关注用户扫描带参数二维码';
			break;
		case 'CLICK': //自定义菜单点击事件
			switch ($postObj->EventKey) {
			//判断是那个按钮
			case 'TRKEY_01_02':
				$content = array();
				$content[] = array('Title' => '标题1', 'Description' => '描述1', 'PicUrl' => 'http://www.renxinjing.cn/images/128.png', 'Url' => 'http://m.baidu.com');
				$content[] = array('Title' => '标题2', 'Description' => '描述2', 'PicUrl' => 'http://www.renxinjing.cn/images/48-12.png', 'Url' => 'http://m.dingping.com');
				break;

			default:
				$content = '点击其他按钮';
				break;
			}
			break;
		default:
			$content = '其他事件类型';
			break;
		}
		if (is_array($content)) {
			$result = $this->transmitNews($postObj, $content);
		} else {
			$result = $this->transmitText($postObj, $content);
		}
		return $result;
	}

	/**
	 * 功能: 处理关注事件,
	 *
	 * @param  [SimpleXMLElement] $postObj
	 * @return [Mixed]
	 */
	private function handleSubscribe($postObj) {
		if (empty($postObj->EventKey)) {
			// 一般关注事件(扫描公众号二维码)
			$content = getUserInfo($postObj, getAccessToken(APP_ID, APP_SECRET));

		} else {
			// 扫描带参数二维码关注
			$sceneID = str_replace("qrscene_", "", $postObj->EventKey);
			switch ($sceneID) {
			// 判断场景参数id // qrscene_123123
			case "123123":
				$content = "针对没有关注用户, 扫描带参数二维码, 场景值: 123123";
				break;
			default:
				$content = "针对没有关注用户, 扫描带参数二维码, 场景值: 其他";
				break;
			}
		}
		return $content;
	}

	/**
	 * 功能: 处理接收的地理位置消息, 调用baidumap.php 方法 setLocation 储存
	 *
	 * @param SimpleXMLElement $postObj
	 * @return String $result
	 */
	public function receiveLocation($postObj) {
		$content = setLocation($postObj->FromUserName, (string) $postObj->Location_X, (string) $postObj->Location_Y);
		$result = $this->transmitText($postObj, $content);

		return $result;
	}

	/**
	 * 功能: 拼接文本xml字符串并返回
	 *
	 * @param SimpleXMLElement $postObj
	 * @param String $content
	 * @return String $xml
	 */
	private function transmitText($postObj, $content) {
		$ToUserName = $postObj->FromUserName;
		$FromUserName = $postObj->ToUserName;
		$createTime = time();
		$xml = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
		        </xml>";
		$xml = sprintf($xml, $ToUserName, $FromUserName, $createTime, $content);
		// 调用logger方法
		logger("info", "TR0001", "返回文本消息字符串: " . $xml);
		return $xml;
	}

	/**
	 * 功能: 拼接图文格式xml字符串并返回
	 * @param SimpleXMLElement $postObj
	 * @param Array $content
	 * @return String $xml
	 */
	private function transmitNews($postObj, $content) {
		if (!is_array($content)) {
			return;
		}
		$item = "<item>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
				</item>";
		$itemStr = '';
		foreach ($content as $value) {
			$itemStr .= sprintf($item, $value['Title'], $value['Description'], $value['PicUrl'], $value['Url']);
		}
		$ToUserName = $postObj->FromUserName;
		$FromUserName = $postObj->ToUserName;
		$CreateTime = time();
		$ArticleCount = count($content);
		$xml = "<xml>
					<ToUserName><![CDATA[" . $ToUserName . "]]></ToUserName>
					<FromUserName><![CDATA[" . $FromUserName . "]]></FromUserName>
					<CreateTime>" . $CreateTime . "</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>" . $ArticleCount . "</ArticleCount>
					<Articles>" . $itemStr . "</Articles>
				</xml>";

		return $xml;
	}

}

?>
