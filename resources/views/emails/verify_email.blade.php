@component('mail::message')
# 你好，{{ $name }}：

请点击此链接验证你的 E-Mail 地址：

@component('mail::button', ['url' => $url])
验证 E-Mail
@endcomponent

此链接自发送后一小时内有效。

{{ config('app.name') }}
@endcomponent
