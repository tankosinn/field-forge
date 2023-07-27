@extends('layouts.user_type.auth')

@push('stylesheets')
    <link rel="stylesheet" href="/assets/js/plugins/photoswipe/dist/photoswipe.css">
@endpush

@section('content')
    @php
        $store_branch = $visit->store_branch()->first();
        $store = $store_branch->store()->first();
        $employee = $visit->employee()->first();
        $files = $visit
            ->files()
            ->get()
            ->groupBy('type');
        
        $stock_outs = $visit->stock_outs()->get();
    @endphp

    <div class="d-flex align-items-center justify-content-between">
        <div class="title">
            <h4 class="mb-0">{{ $store_branch->name }}</h4>
            <p class="mb-0">{{ $store->name }}</p>
            <span class="font-weight-bold text-sm">
                {{ $visit->created_at->format('H.i') }} -
                {{ $visit->status == 2 ? $visit->updated_at->format('H.i') : 'Devam Ediyor' }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mt-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0"><i class="fas fa-receipt" aria-hidden="true"></i> Fiş Fotoğrafı</h6>
                </div>
                <div class="card-body py-0">
                    <div class="photo-swipe-gallery">
                        @if (isset($files[1]))
                            @foreach ($files[1] as $image)
                                <a href="{{ url('storage/visit/' . $image->file) }}" target="_blank" data-cropped="true">
                                    <img src="{{ url('storage/visit/' . $image->file) }}" alt="" />
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="m-0"><i class="fas fa-arrow-right" aria-hidden="true"></i> Giriş Fotoğrafı</h6>
                </div>
                <div class="card-body py-0">
                    <div class="photo-swipe-gallery">
                        @if (isset($files[2]))
                            @foreach ($files[2] as $image)
                                <a href="{{ url('storage/visit/' . $image->file) }}" target="_blank" data-cropped="true">
                                    <img src="{{ url('storage/visit/' . $image->file) }}" alt="" />
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="m-0"><i class="fas fa-arrow-left" aria-hidden="true"></i> Çıkış Fotoğrafı</h6>
                </div>
                <div class="card-body py-0">
                    <div class="photo-swipe-gallery">
                        @if (isset($files[3]))
                            @foreach ($files[3] as $image)
                                <a href="{{ url('storage/visit/' . $image->file) }}" target="_blank" data-cropped="true">
                                    <img src="{{ url('storage/visit/' . $image->file) }}" alt="" />
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="m-0"><i class="fas fa-cube" aria-hidden="true"></i> Stokta Olmayan Ürünler</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table align-items-center mb-0 table-striped">
                        <tbody>
                            @if ($stock_outs)
                                @foreach ($stock_outs as $stock_out)
                                    <tr>
                                        <td>
                                            <span class="px-4 text-secondary text-xs font-weight-bold">
                                                {{ $stock_out->product()->first()->name }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6><i class="fa fa-bell text-gradient text-success" aria-hidden="true"></i> Bildirimler</h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline timeline-one-side">
                        @foreach ($visit->logs()->orderBy('created_at', 'DESC')->get() as $log)
                            <div class="timeline-block mb-3">
                                @php
                                    switch ($log->type) {
                                        case 1:
                                            $notification = [
                                                'icon' => 'fas fa-bolt text-info text-gradient',
                                                'title' => 'Ziyarete Başlandı',
                                            ];
                                            break;
                                        case 2:
                                            $notification = [
                                                'icon' => 'fas fa-camera text-dark text-gradient',
                                                'title' => 'Fotoğraf Yüklendi',
                                            ];
                                            break;
                                        case 3:
                                            $notification = [
                                                'icon' => 'fas fa-check text-success text-gradient',
                                                'title' => 'Ziyaret Tamamlandı',
                                            ];
                                            break;
                                        case 4:
                                            $notification = [
                                                'icon' => 'fas fa-wrench text-warning text-gradien',
                                                'title' => 'Ziyaret Güncellendi',
                                            ];
                                            break;
                                    }
                                @endphp
                                <span class="timeline-step">
                                    <i class="{{ $notification['icon'] }}"></i>
                                </span>
                                <div class="timeline-content">
                                    <div class="d-flex align-items-flex-center justify-content-between">
                                        <div class="timeline-info">
                                            @if ($log->note)
                                                <span class="p-1 bg-dark text-white rounded text-xxs">
                                                    <i class="fas fa-envelope"></i>
                                                    Not Bırakıldı
                                                </span>
                                            @endif
                                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                {{ $notification['title'] }}
                                            </h6>
                                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                {{ $log->created_at->format('H.i d.m.Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="javascript: void(0);" class="log text-sm" data-bs-toggle="modal"
                                        data-bs-target="#log-modal-{{ $log->id }}">
                                        <i class="fa fa-xs fa-external-link"></i>
                                        Detaylı Görünüm
                                    </a>
                                    <div class="modal fade" id="log-modal-{{ $log->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div
                                            class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-dark font-weight-bold mb-0">
                                                        <i class="{{ $notification['icon'] }}"></i>
                                                        {{ $notification['title'] }}
                                                    </h5>
                                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                        {{ $log->created_at->format('H.i d.m.Y') }}
                                                    </p>
                                                    @if ($log->type == 2)
                                                        <div class="mt-4">
                                                            <label class="text-sm">
                                                                <i class="fas fa-camera"></i> Fotoğraf
                                                            </label>
                                                            <div class="form-control">
                                                                <img
                                                                    src="{{ url('storage/visit/' . $log->file()->first()->file) }}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($log->note)
                                                        <div class="mt-4">
                                                            <label class="text-sm">
                                                                <i class="fas fa-envelope"></i> Not
                                                            </label>
                                                            <div class="form-control">
                                                                {{ $log->note }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <a href="javascript: void(0);"
                                                        class="btn btn-info d-block mt-4 show-location"
                                                        data-latitude="{{ $log->latitude }}"
                                                        data-longitude="{{ $log->longitude }}">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        Konumu Göster
                                                    </a>
                                                    <div class="location mt-4 d-none">
                                                        <label class="text-sm">
                                                            <i class="fas fa-map-marker-alt"></i> Konum
                                                        </label>
                                                        <div class="location-insert"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                                        <i class="fas fa-times"></i>
                                                        Kapat
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/plugins/photoswipe/dist/umd/photoswipe.umd.min.js"></script>
    <script src="/assets/js/plugins/photoswipe/dist/umd/photoswipe-lightbox.umd.min.js"></script>

    <script type="text/javascript">
        var lightbox = new PhotoSwipeLightbox({
            gallery: '.photo-swipe-gallery',
            children: 'a',
            showHideAnimationType: 'fade',
            pswpModule: PhotoSwipe
        });

        lightbox.init();

        (function() {
            $(function() {
                $(".show-location").on('click', function() {
                    var latitude = $(this).data('latitude');
                    var longitude = $(this).data('longitude');
                    var modal = $(this).closest(".modal-body");

                    $(modal).find('.location').removeClass('d-none');

                    $(modal).find('.location-insert').html(
                        '<iframe class="rounded" width="100%" height="380" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' +
                        latitude + ',' + longitude + '&output=embed"></iframe>');

                    $(this).remove();

                });
            });
        })(jQuery);
    </script>
@endpush
