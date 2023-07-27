@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100"
                style="background-image: url('/assets/img/ivancik.jpg');">
                <span class="mask bg-gradient-dark"></span>
                <div
                    class="card-body position-relative z-index-1 h-100 p-3 d-flex align-items-center justify-content-between flex-wrap">
                    <div class="card-content my-2">
                        <h6 class="text-white font-weight-bolder">Hoş geldin, {{ Auth::user()->name }}!</h6>
                        <p class="text-light m-0">Daha detaylı istatistiklere ve bilgilere, rapor oluşturarak
                            ulaşabilirsiniz.</p>
                    </div>
                    <a class="btn btn-round btn-outline-white mb-0" href="{{ url('rapor/olustur') }}">
                        <i class="fas fa-bolt"></i>
                        Rapor Oluştur
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-days nav nav-tabs days mt-4">
        @php
            $dayOfWeek = \Carbon\Carbon::now()->dayOfWeekIso - 1;
        @endphp
        @foreach (getDays() as $slug => $day)
            <a href="{{ url('anasayfa/' . $slug) }}"
                class="btn btn-dark day {{ Request::is('anasayfa/' . $slug) || ((Request::is('anasayfa') || Request::is('/')) && $day['index'] == $dayOfWeek) ? 'active' : '' }}">{{ $day['title'] }}</a>
        @endforeach
    </div>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Ziyaret Edilmeyen</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['count_routes'] - $stats['count_visited'] }}
                                    <span class="text-warning text-sm font-weight-bold">Satış Noktası</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end d-flex align-items-center justify-content-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-exclamation opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Ziyareti Devam Eden</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['count_visiting'] }}
                                    <span class="text-info text-sm font-weight-bold">Satış Noktası</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end d-flex align-items-center justify-content-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-spinner opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Ziyaret Edilen</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['count_visited'] }}
                                    <span class="text-success text-sm font-weight-bold">Satış Noktası</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end d-flex align-items-center justify-content-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-check opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Ort. Ziyaret Süresi</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['average_time'] > 0 ? gmdate('i', $stats['average_time']) : '-' }} dk
                                    <span
                                        class="text-muted text-sm font-weight-bolder">{{ $stats['average_time'] > 0 ? gmdate('s', $stats['average_time']) : '-' }}
                                        sn</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end d-flex align-items-center justify-content-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-clock opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Route List -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0"><i class="fas fa-route"></i> Güzergahlar</h6>
                        <a href="{{ url('rapor/satis-noktalari?generated=true&from=' . $rangeWeek['start'] . '&until=' . $rangeWeek['end'] . '&type=1') }}"
                            target="_blank" class="btn btn-sm btn-warning"><i class="fas fa-bolt"></i> Haftalık Rapor</a>
                    </div>
                </div>
                <div class="card-body px-0 py-0">
                    <div class="nav nav-tabs days employee-nav px-3">
                        @foreach ($employees as $i => $employee)
                            <a href="#routes-{{ $employee->id }}" class="btn btn-light day {{ $i == 0 ? 'active' : '' }}"
                                data-bs-toggle="tab">{{ $employee->name }}</a>
                        @endforeach
                    </div>
                    <div class="tab-content p-4">
                        @foreach ($employees as $i => $employee)
                            <div class="tab-pane fade in show {{ $i == 0 ? 'active' : '' }}"
                                id="routes-{{ $employee->id }}">
                                <div class="list-group">
                                    @foreach ($routes[$employee->id] as $route)
                                        @php
                                            $store_branch = $route->store_branch()->first();
                                            $store = $store_branch->store()->first();
                                            $visit = $visits[$route->id];
                                        @endphp
                                        <div
                                            class="route list-group-item border-1 d-flex justify-content-between p-0 mb-2 border-radius-lg {{ $visit ? ($visit->status == 1 ? 'bg-info text-white' : 'bg-dark text-white') : 'bg-white text-dark' }}">
                                            <div class="visit d-flex align-items-center w-100"
                                                data-uri="{{ url('ziyaret/' . $route->id) }}"
                                                data-json='{{ json_encode(['employee_route' => $route->id, 'store' => $store->id, 'store_branch' => $store_branch->id, 'day' => $i]) }}'
                                                data-visited="{{ $visit ? true : false }}">
                                                @switch($visit?->status)
                                                    @case(1)
                                                        <button type="button" class="actions me-3 bg-white text-dark">
                                                            <i class="fas fa-spinner"></i>
                                                        </button>
                                                    @break

                                                    @case(2)
                                                        <button type="button" class="actions me-3 bg-white text-primary">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @break

                                                    @default
                                                        <button type="button" class="actions me-3 bg-white text-danger">
                                                            <i class="fas fa-exclamation"></i>
                                                        </button>
                                                @endswitch
                                                <div class="d-flex flex-column">
                                                    <h6
                                                        class="mb-1 {{ $visit ? 'text-white' : 'text-dark' }} text-sm text-uppercase">
                                                        {{ $store_branch->name }}</h6>
                                                    <span class="text-xs text-uppercase mb-1">{{ $store->name }}</span>
                                                    @if ($visit)
                                                        <span
                                                            class="text-light text-xs">{{ $visit->created_at->format('H:i') }}
                                                            -
                                                            {{ $visit->status == 2 ? $visit->updated_at->format('H:i') : 'Devam Ediyor' }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($visit)
                                                <div class="tools d-flex align-items-center">
                                                    <a href="{{ url('ziyaret/' . $visit->id . '/goruntule') }}"
                                                        target="_blank" class="btn btn-xs btn-light">
                                                        Görüntüle
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Stock Outs -->
@endsection
