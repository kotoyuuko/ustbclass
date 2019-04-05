@extends('layouts.app')
@section('title', '编辑个人资料')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">编辑个人资料</div>

          <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
              @csrf
              <input type="hidden" name="_method" value="PUT">

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">姓名</label>

                <div class="col-md-6">
                  <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name) }}" required autofocus>

                  @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail 地址</label>

                <div class="col-md-6">
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" required>

                  @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="elearning_id" class="col-md-4 col-form-label text-md-right">学号</label>

                <div class="col-md-6">
                  <input id="elearning_id" type="text" class="form-control{{ $errors->has('elearning_id') ? ' is-invalid' : '' }}" name="elearning_id" value="{{ old('elearning_id', $user->elearning_id) }}">

                  @if ($errors->has('elearning_id'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('elearning_id') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="elearning_pwd" class="col-md-4 col-form-label text-md-right">教务系统密码</label>

                <div class="col-md-6">
                  <input id="elearning_pwd" type="text" class="form-control{{ $errors->has('elearning_pwd') ? ' is-invalid' : '' }}" name="elearning_pwd" value="{{ old('elearning_pwd', $user->elearning_pwd) }}">

                  @if ($errors->has('elearning_pwd'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('elearning_pwd') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    保存
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
