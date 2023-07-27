@extends('layouts.user_type.auth')

@section('content')
    <div class="nav nav-tabs days mb-4">
        <a href="#day-0" class="btn btn-dark day active" data-bs-toggle="tab">Pazartesi</a>
        <a href="#day-1" class="btn btn-dark day" data-bs-toggle="tab">Salı</a>
        <a href="#day-2" class="btn btn-dark day" data-bs-toggle="tab">Çarşamba</a>
        <a href="#day-3" class="btn btn-dark day" data-bs-toggle="tab">Perşembe</a>
        <a href="#day-4" class="btn btn-dark day" data-bs-toggle="tab">Cuma</a>
        <a href="#day-5" class="btn btn-dark day" data-bs-toggle="tab">Cumartesi</a>
    </div>
    <div class="routes">
        <div class="tab-content">
            @foreach ($routes as $i => $routeList)
            <div class="tab-pane fade in show {{ $i == 0 ? 'active' : '' }}" id="day-{{ $i }}">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0"><i class="fas fa-route"></i> Güzergahlar</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="list-group">
                            @foreach ($routeList as $route)
                                @php
                                    $store_branch = $route->store_branch()->first();
                                    $store = $store_branch->store()->first();
                                    $rangeWeek = rangeWeek(date('Y-m-d'));
                                    $visit = $route->visit()->where('created_at', '>=', $rangeWeek['start'])->where('created_at', '<=', $rangeWeek['end'])->first();
                                @endphp
                                <div class="route list-group-item border-1 d-flex justify-content-between p-0 mb-2 border-radius-lg {{ $visit ? 'bg-dark text-white' : 'bg-white text-dark' }}">
                                    <div class="visit d-flex align-items-center w-100" data-uri="{{ url('ziyaret/' . $route->id) }}" data-json='{{ json_encode(['employee_route' => $route->id, 'store' => $store->id, 'store_branch' => $store_branch->id, 'day' => $i]) }}' data-visited="{{ $visit ? true : false }}">
                                        <button type="button" class="actions me-3 {{ $visit ? 'bg-white text-primary' : 'bg-dark text-white' }}">
                                            <i class="fas fa-{{ $visit ? 'check' : 'clock' }}"></i>
                                        </button>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 {{ $visit ? 'text-white' : 'text-dark' }} text-sm text-uppercase">{{ $store_branch->name }}</h6>
                                            <span class="text-xs text-uppercase">{{ $store->name }}</span>
                                        </div>
                                    </div>
                                    <div class="tools d-flex align-items-center">
                                        <button type="button" class="actions get-address" data-address="<?= stripslashes(trim($store_branch->address)) ?>">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function($) {
            $(function() {
                $(".get-address").on('click', function() {
                    var address = $(this).data('address');
                    navigator.clipboard.writeText(address);
                    $.confirm({
                        title: 'Adres Kopyalandı.',
                        content: address,
                        theme: 'modern',
                        closeIcon: true,
                        scrollToPreviousElement: false, // add this line 
                        scrollToPreviousElementAnimate: false, // add this line 
                        buttons: {
                            cancel: {
                                text: 'Tamam',
                                btnClass: 'btn-blue'
                            }
                        }
                    });
                });

                $(".visit").on('click', function() {
                    var uri = $(this).data('uri');
                    var data = $(this).data('json');
                    var visited = $(this).data('visited');
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            data.latitude = position.coords.latitude;
                            data.longitude = position.coords.longitude;
                            $.confirm({
                                title: 'Ziyareti ' + (visited ? 'Güncelle' : 'Başlat'),
                                content: 'Ziyaret noktasında olduğunuzdan ve ' + (visited ? 'güncellemek' : 'başlatmak') + ' istediğinizden emin misiniz?',
                                theme: 'modern',
                                closeIcon: true,
                                autoClose: 'cancel|10000',
                                scrollToPreviousElement: false, 
                                scrollToPreviousElementAnimate: false,
                                buttons: {
                                    confirm: {
                                        text: 'Evet',
                                        btnClass: 'btn-red',
                                        action: function (e) { 
                                            if(!visited) {
                                                RequestHandler.Request('ziyaret/yeni', null, data, function(response) {
                                                    if(response.status) {
                                                        window.location.href = uri;
                                                    }
                                                },
                                                function(error) {
                                                    RequestHandler.ErrorHandler(error.responseJSON.errors);
                                                });
                                            } else {
                                                window.location.href = uri;
                                            }
                                        }
                                    },
                                    cancel: {
                                        text: 'Hayır',
                                        btnClass: 'btn-blue'
                                    }
                                }
                            });
                        },
                        function() {
                            toastr.warning('Konumunuzunu açmadan ziyarete başlayamazsınız.');
                        },
                        {
                            enableHighAccuracy: true
                        }
                    );
                });

                
            });
        })(jQuery);
    </script>
@endpush