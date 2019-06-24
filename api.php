<?php
	if(version_compare(PHP_VERSION,'5.3.0', '<')){
		echo '当前版本为'.phpversion().'小于5.3.0哦';
	}else {
		echo '当前版本为' . PHP_VERSION . '大于5.3.0';
	}
	require 'sdk/autoload.php';
	use \Qiniu\Auth;
	use \Qiniu\Storage\UploadManager;	// 引入上传类
	use \Qiniu\Storage\BucketManager;


	class QiNiuApi
	{
		// 用于签名的公钥和私钥
		private $accessKey = 'tJZ6tKImv_xfhIJA75AsXWKQVvsU1vTxR6QQUwG0';
		private $secretKey = 'nFV3A1gRpya4Z1vCZz8lul4dsE7in5boPoDK1pCa';
		private $bucket = 'laojiang';
		protected $auth;

		public function __construct() {
			// 初始化签权对象
			$this->auth = new Auth($this->accessKey, $this->secretKey);
		}

		public function Upload($key = 'my-php-logo.png',$localFilePath = './php-logo.png') {
			// 构建鉴权对象
			// 生成上传 Token
			$token = $this->auth->uploadToken($this->bucket);

			// 初始化 UploadManager 对象并进行文件的上传。
			$uploadMgr = new UploadManager();
			// 调用 UploadManager 的 putFile 方法进行文件的上传。
			list($ret, $err) = $uploadMgr->putFile($token, $key, $localFilePath);
			echo "\n====> putFile result: \n";
			if ($err !== null) {
				var_dump($err);
			} else {
				var_dump($ret);
			}
		}

		public function Delete($keys) {
			$config = new \Qiniu\Config();
			$bucketManager = new BucketManager($this->auth, $config);
			//每次最多不能超过1000个

			$ops = $bucketManager->buildBatchDelete($this->bucket, $keys);
			list($ret, $err) = $bucketManager->batch($ops);
			if ($err) {
				print_r($err);
			} else {
				print_r($ret);
			}
		}

		public function hasExist($key) {
			$config = new \Qiniu\Config();
			$bucketManager = new \Qiniu\Storage\BucketManager($this->auth, $config);
			list($fileInfo, $err) = $bucketManager->stat($this->bucket, $key);
			if ($err) {
				print_r($err);
			} else {
				print_r($fileInfo);
			}
		}

	}


	$qn = new QiNiuApi();
//	$qn->Upload('2019/04/微信图片_20190402175557.gif', 'E:\web\wordpress\wp-content\uploads\2019\04\微信图片_20190402175557.gif');
    $keys = array(
	    '2019/04/2019042809091717.jpg',
    );
	$qn->Delete($keys);
