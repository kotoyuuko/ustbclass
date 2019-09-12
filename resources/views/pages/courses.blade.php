@extends('layouts.app')
@section('title', '课程表')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card course-card">
        <div class="card-header text-center">
          <strong>第 {{ $week }} 周课程表</strong><br>
          <small>最后更新：{{ $last_updated_at->diffForHumans() }}</small>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered course-table text-center">
            <thead>
              <tr>
                <th scope="col" width="2%">&nbsp;</th>
                <th scope="col" width="14%">一</th>
                <th scope="col" width="14%">二</th>
                <th scope="col" width="14%">三</th>
                <th scope="col" width="14%">四</th>
                <th scope="col" width="14%">五</th>
                <th scope="col" width="14%">六</th>
                <th scope="col" width="14%">日</th>
              </tr>
            </thead>
            <tbody>
              @for ($seq = 1; $seq <= 6; $seq++) <tr>
                <th scope="row">{{ $seq }}</th>
                @for ($day = 1; $day <= 7; $day++) @php $time=($day - 1) * 6 + $seq; @endphp <td>
                  @if (isset($table[$time]) && count($table[$time]) > 0)
                  @foreach ($table[$time] as $item)
                  <div class="course-item">
                    {{ $item['name'] }}<br>
                    {{ $item['location'] }}
                  </div>
                  @endforeach
                  @else
                  &nbsp;
                  @endif
                  </td>
                  @endfor
                  </tr>
                  @endfor
            </tbody>
          </table>
        </div>
        <div class="card-footer text-center">
          <div class="float-left">
            @if ($week > 1)
            <a href="{{ route('courses', $week - 1) }}">&lt; 上一周</a>
            @else
            &nbsp;
            @endif
          </div>
          <span>
            <a href="{{ route('courses.send', $week) }}">推送日历</a>
          </span>
          <div class="float-right">
            @if ($week < 16) <a href="{{ route('courses', $week + 1) }}">下一周 &gt;</a>
              @else
              &nbsp;
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
