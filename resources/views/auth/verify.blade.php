@extends('layouts.app')
@section('title', 'E-Mail 验证')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">验证 E-Mail 地址</div>

          <div class="card-body">
            @if (session('resent'))
              <div class="alert alert-success" role="alert">
                一封含有验证链接的邮件已经发送到你的 E-Mail
              </div>
            @endif

            请验证你的 E-Mail 地址！如果你没有收到验证邮件, <a href="{{ route('verification.resend') }}">点击此处重新发送</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
