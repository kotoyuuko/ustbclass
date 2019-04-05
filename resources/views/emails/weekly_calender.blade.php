@component('mail::message')
# 你好，{{ $name }}：

附件内是你下周的课程表日历文件，请点击导入。

生成时间：{{ $gentime }}。

{{ config('app.name') }}
@endcomponent
