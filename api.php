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
		private $accessKey = 'iRodR8NP2DhLCLXxydYLJfaIjxIyY7eC5cdCgxhi';
		private $secretKey = '5IaxA510oF7RBL-tL74W14nNDnmCvKT4Qirl4vXV';
		private $bucket = 'wptest';
		protected $auth;

		public function __construct() {
			// 初始化签权对象
			$this->auth = new Auth($this->accessKey, $this->secretKey);
		}

		protected function Auth()
		{

		}

		public function Upload() {
			// 需要填写你的 Access Key 和 Secret Key
			$accessKey ="your accessKey";
			$secretKey = "your secretKey";
			$bucket = "your bucket name";
			// 构建鉴权对象
			$auth = new Auth($accessKey, $secretKey);
			// 生成上传 Token
			$token = $auth->uploadToken($bucket);
			// 要上传文件的本地路径
			$filePath = './php-logo.png';
			// 上传到七牛后保存的文件名
			$key = 'my-php-logo.png';
			// 初始化 UploadManager 对象并进行文件的上传。
			$uploadMgr = new UploadManager();
			// 调用 UploadManager 的 putFile 方法进行文件的上传。
			list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
			echo "\n====> putFile result: \n";
			if ($err !== null) {
				var_dump($err);
			} else {
				var_dump($ret);
			}
		}

		public function Delete() {
			$accessKey = getenv('QINIU_ACCESS_KEY');
			$secretKey = getenv('QINIU_SECRET_KEY');
			$bucket = getenv('QINIU_TEST_BUCKET');
			$auth = new Auth($accessKey, $secretKey);
			$config = new \Qiniu\Config();
			$bucketManager = new BucketManager($auth, $config);
			//每次最多不能超过1000个
			$keys = array(
				'qiniu.mp4',
				'qiniu.png',
				'qiniu.jpg'
			);
			$ops = $bucketManager->buildBatchDelete($bucket, $keys);
			list($ret, $err) = $bucketManager->batch($ops);
			if ($err) {
				print_r($err);
			} else {
				print_r($ret);
			}
		}

		public function hasExist() {
			$accessKey = getenv('QINIU_ACCESS_KEY');
			$secretKey = getenv('QINIU_SECRET_KEY');
			$bucket = getenv('QINIU_TEST_BUCKET');
			$key = "qiniu.mp4";
			$auth = new Auth($accessKey, $secretKey);
			$config = new \Qiniu\Config();
			$bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
			list($fileInfo, $err) = $bucketManager->stat($bucket, $key);
			if ($err) {
				print_r($err);
			} else {
				print_r($fileInfo);
			}
		}

	}







	// 生成上传Token
	$token = $auth->uploadToken($bucket);



	var_dump($token);
