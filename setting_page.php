<?php
/**
 *  插件设置页面
 * User: zdl25
 * Date: 2019/3/15
 * Time: 17:43
 */
function wposs_setting_page() {
// 如果当前用户权限不足
if (!current_user_can('administrator')) {
	wp_die('Insufficient privileges!');
}

$wposs_options = get_option('wposs_options', True);
if (!empty($_POST)) {
    if($_POST['type'] == 'info_set') {

        foreach ($wposs_options as $k => $v) {
            if ($k =='no_local_file') {
	            $wposs_options[$k] = (isset($_POST[$k])) ? 'true' : 'false';
            } else {
	            $wposs_options[$k] = (isset($_POST[$k])) ? trim(stripslashes($_POST[$k])) : '';
            }
        }
	    // 不管结果变没变，有提交则直接以提交的数据 更新 wposs_options
        update_option('wposs_options', $wposs_options);

        # 更新另外两个wp自带的上传相关属性的值
        # 替换 upload_path 的值
        $upload_path = trim(trim(stripslashes($_POST['upload_path'])), '/');
        update_option('upload_path', ($upload_path == '') ? ('wp-content/uploads') : ($upload_path));
        # 替换 upload_url_path 的值
        update_option('upload_url_path', trim(trim(stripslashes($_POST['upload_url_path'])), '/'));

?>
    <div class="updated"><p><strong>设置已保存！</strong></p></div>

<?php

    }
}

?>


<div class="wrap" style="margin: 10px;">
    <h2>WP OSS静态存储</h2>
    <form name="form1" method="post" action="<?php echo wp_nonce_url('./admin.php?page=' . WPOSS_BASEFOLDER . '/actions.php'); ?>">
        <table class="form-table">
            <tr>
                <th>
                    <legend>Bucket名称</legend>
                </th>
                <td>
                    <input type="text" name="bucket" value="<?php echo esc_attr($wposs_options['bucket']); ?>" size="50"
                           placeholder="BUCKET"/>

                    <p>请先访问 <a href="https://oss.console.aliyun.com/overview" target="_blank">阿里云OSS控制台</a> 创建
                        <code>Bucket</code> ，再填写以上内容。示例: itbulu</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>EndPoint 设置</legend>
                </th>
                <td>
                    <input type="text" name="endpoint" value="<?php echo esc_attr($wposs_options['endpoint']); ?>" size="50"
                           placeholder="http://oss-cn-shanghai.aliyuncs.com"/>
                    <p>请打开 <code>https://help.aliyun.com/document_detail/31837.html</code> 查看OSS所属地域对应的EndPoint。 </p>
                    <p>若您的wordpress部署在非阿里云服务器请选择 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-1">外网EndPoint</a> (对应表格第3列); </p>
                    <p>若部署在阿里云不同VPC下使用 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-1">内网EndPoint</a> (对应表格第5列); </p>
                    <p>若部署在同一VPC可使用 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-2">VPC EndPoint</a> (对应表格第3列)。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>AccessKeyId</legend>
                </th>
                <td><input type="text" name="accessKeyId" value="<?php echo esc_attr($wposs_options['accessKeyId']); ?>" size="50" placeholder="AccessKeyId"/></td>
            </tr>
            <tr>
                <th>
                    <legend>AccessKeySecret</legend>
                </th>
                <td>
                    <input type="text" name="accessKeySecret" value="<?php echo esc_attr($wposs_options['accessKeySecret']); ?>" size="50" placeholder="AccessKeySecret"/>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>不在本地保留备份</legend>
                </th>
                <td>
                    <input type="checkbox"
                           name="no_local_file" <?php if (esc_attr($wposs_options['no_local_file']) == 'true') {
						echo 'checked="TRUE"';
					}
					?> />

                    <p>建议不勾选</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>本地文件夹：</legend>
                </th>
                <td>
                    <input type="text" name="upload_path" value="<?php echo get_option('upload_path'); ?>" size="50"
                           placeholder="请输入上传文件夹"/>

                    <p>附件在服务器上的存储位置，例如： <code>wp-content/uploads</code> （注意不要以“/”开头和结尾），根目录请输入<code>.</code>。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>URL前缀：</legend>
                </th>
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo get_option('upload_url_path'); ?>" size="50"
                           placeholder="请输入URL前缀"/>

                    <p><b>注意：</b></p>

                    <p>1）URL前缀的格式为 <code>{http或https}://{bucket}.{外网EndPoint}</code> （“本地文件夹”为 <code>.</code> 时），或者 <code>http://{cos域名}/{本地文件夹}</code>
                        ，“本地文件夹”务必与上面保持一致（结尾无 <code>/</code> ）。</p>

                    <p>2）OSS中的存放路径（即“文件夹”）与上述 <code>本地文件夹</code> 中定义的路径是相同的（出于方便切换考虑）。</p>

                    <p>3）如果需要使用 <code>独立域名</code> ，直接将 <code>{bucket}.{外网EndPoint}</code> 替换为 <code>您的独立域名</code> ，并在OSS控制台域名管理里面<code>绑定该域名</code>。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>更新选项</legend>
                </th>
                <td><input type="submit" name="submit" value="更新"/></td>
            </tr>
        </table>
        <input type="hidden" name="type" value="info_set">
    </form>
</div>
<?php
}
?>