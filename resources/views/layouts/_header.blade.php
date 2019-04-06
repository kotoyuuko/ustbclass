<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <a class="navbar-brand " href="{{ url('/') }}">
      {{ config('app.name') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('root') }}">课程表</a>
        </li>
        @auth
          <li class="nav-item">
            <a class="nav-link" href="{{ route('users.edit', Auth::id()) }}">个人资料</a>
          </li>
        @endauth
        <li class="nav-item">
          <a class="nav-link" href="{{ route('help') }}">帮助</a>
        </li>
      </ul>

      <ul class="navbar-nav navbar-right">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="https://gravatar.loli.net/avatar/4fc6d7ad656f8aa8d88def86f18bd953?s=24" class="rounded-circle" width="24px" height="24px">
              {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" id="logout" href="#"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
