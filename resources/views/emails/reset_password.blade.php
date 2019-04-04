@component('mail::message')
# 你好：

请点击此链接重设你的登录密码：

@component('mail::button', ['url' => $url])
重设密码
@endcomponent

{{ config('app.name') }}
@endcomponent
