@component('mail::message')
# 你好，{{ $name }}：

这是你下周的课程表日历，请点击下面的链接或附件导入：

@if ($link)
  @component('mail::button', ['url' => $link])
  导入日历
  @endcomponent
@endif

生成时间：{{ $gentime }}。

{{ config('app.name') }}
@endcomponent
