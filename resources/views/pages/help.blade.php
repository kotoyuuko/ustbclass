@extends('layouts.app')
@section('title', '帮助')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">帮助</div>
        <div class="card-body">
          <h5>使用教程</h5>
          <ol>
            <li>注册账号，并验证 E-Mail</li>
            <li>在个人资料页面设置学号和教务系统密码</li>
            <li>等待周末系统自动发送邮件</li>
            <li>接到每周邮件后点击附件，选择导入日历</li>
          </ol>
          <h5>手机自带日历软件及邮件客户端兼容性</h5>
          <ul>
            <li>iPhone：可用（Apple iPhone 6s/iOS 12.2）</li>
            <li>Google：没有测试机，理论上可用</li>
            <li>SONY：可用（SONY Xperia XZ2/Android 9.0）</li>
            <li>Samsung：可用（Samsung Galaxy S8/Samsung Experience 9.0）</li>
            <li>HTC：没有测试机，理论上可用</li>
            <li>Huawei：可用（Huawei Honor V20/EMUI 9.0）</li>
            <li>Xiaomi：<strong>不可用</strong>，因 MIUI 日历阉割了日历导入功能，需要安装「Sol 日历」软件导入日历（Xiaomi MIX 2/MIUI 10）</li>
            <li>Meizu：可用（Meizu 15/7.2.0.0A）</li>
            <li>vivo：可用（vivo iQOO/Funtouch OS 9）</li>
            <li>Smartisan：可用（Smartisan R1/Smartisan OS 6.6.6.1_TNT）</li>
            <li>其他品牌手机因没有测试机暂时没有测试，不保证可用。</li>
            <li>同时欢迎反馈更多机型的兼容情况，直接 <a href="https://github.com/kotoyuuko/ustbclass" target="_blank">GitHub</a> 上提 issue 即可。</li>
          </ul>
          <h5>第三方日历软件及邮件客户端推荐</h5>
          <ul>
            <li>iOS：请使用 iOS 自带的日历软件及邮件客户端</li>
            <li>Android：小米手机推荐使用「Sol 日历」和「网易邮箱大师」，非小米手机请使用自带的软件</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
