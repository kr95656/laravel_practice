<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand">
            <img src="/images/logo-1.png" style="height: 39px" alt="Melpit">
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @guest
                    {{--  非ログイン状態  --}}
                    <li class="nav-item">
                        <a class="btn btn-secondary ml-3" href="{{ route('register') }}">会員登録</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-secondary ml-3" href="{{ route('login') }}">ログイン</a>
                    </li>
                @else
                    {{--  ログイン状態  --}}
                    <li class="nav-item dropdown ml-2">
                        <a href="#" id="navbarDropdown" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if (!empty($user->avatar_file_name))
                                <img src="/storage/avatars/{{$user->avatar_file_name}}" class="rounded-circle" style="object-fit: cover; width: 35px; height: 35px;">
                            @else
                                <img src="/images/avatar-default.svg" class="rounded-circle" style="object-fit: cover; width: 35px; height: 35px;">
                            @endif
                            {{ $user->name }} <span class="caret"></span>

                            {{--  ドロップダウンメニュー  --}}
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('mypage.edit-profile') }}">
                                    <i class="far fa-address-card text-left" style="width: 30px"></i>プロフィール編集
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt text-left" style="width: 30px"></i>ログアウト
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
