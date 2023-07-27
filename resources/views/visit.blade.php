@extends('layouts.user_type.auth')

@push('stylesheets')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/js/plugins/photo-gallery/photo-gallery.css">
@endpush

@section('content')
    @php
        $store_branch = $visit->store_branch()->first();
        $store = $store_branch->store()->first();
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
    <form id="FormVisit">
        <input type="hidden" name="id" value="{{ $visit->id }}">
        <div class="card mb-4">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-receipt"></i> Fiş Fotoğrafı
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="gallery" data-type="1" data-visit="{{ $visit->id }}">
                    <div class="image tool">
                        <div class="add-image">
                            <i class="fas fa-plus"></i>
                            Fotoğraf Ekle
                        </div>
                    </div>
                    @if (isset($images[1]))
                        @foreach ($images[1] as $image)
                            <div class="image">
                                <img src="{{ url('storage/visit/' . $image->file) }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-arrow-right"></i> Giriş Fotoğrafı
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="gallery" data-type="2" data-visit="{{ $visit->id }}">
                    <div class="image tool">
                        <div class="add-image">
                            <i class="fas fa-plus"></i>
                            Fotoğraf Ekle
                        </div>
                    </div>
                    @if (isset($images[2]))
                        @foreach ($images[2] as $image)
                            <div class="image">
                                <img src="{{ url('storage/visit/' . $image->file) }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-arrow-left"></i> Çıkış Fotoğrafı
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="gallery" data-type="3" data-visit="{{ $visit->id }}">
                    <div class="image tool">
                        <div class="add-image">
                            <i class="fas fa-plus"></i>
                            Fotoğraf Ekle
                        </div>
                    </div>
                    @if (isset($images[3]))
                        @foreach ($images[3] as $image)
                            <div class="image">
                                <img src="{{ url('storage/visit/' . $image->file) }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header pb-3 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-cube"></i> Stokta Olmayan Ürünler
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table align-items-center justify-content-between mb-0 table-striped" id="products">
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td class="p-0">
                                    <div class="d-flex justify-content-between align-items-center p-3">
                                        <p class="font-weight-bold mb-0">{{ $product->name }}</p>
                                        <label class="label-checkbox">
                                            <input type="checkbox" name="stock_outs[]" value="{{ $product->id }}"
                                                {{ isset($stock_outs[$product->id]) ? 'checked' : '' }}>
                                            <span class="checkbox"></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">
                    <i class="fas fa-pen"></i> Not
                </h6>
            </div>
            <div class="card-body p-3">
                <textarea name="note" class="form-control" rows="5"></textarea>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between">
                <button type="button" onclick="window.location.reload();" class="btn bg-gradient-warning btn-md"><i
                        class="fas fa-sync-alt"></i> {{ 'İptal Et' }}</button>
                <button type="button" id="submit" class="btn bg-gradient-dark btn-md"><i class="fas fa-check"></i>
                    {{ 'Kaydet' }}</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ url('/') }}/assets/js/plugins/photo-gallery/photo-gallery.js"></script>
    <script>
        (function($) {
            $(function() {
                $("#products tbody tr td").on("click", function() {
                    var checkbox = $(this).find('input[type="checkbox"]');
                    if ($(checkbox).prop("checked")) {
                        $(checkbox).prop("checked", false);
                    } else {
                        $(checkbox).prop("checked", true);
                    }
                });

                $("#submit").on('click', function() {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            RequestHandler.Request('ziyaret/kaydet', '#FormVisit', {
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                }, function(response) {
                                    window.location.href = "{{ url('/bugun') }}";
                                },
                                function(error) {
                                    RequestHandler.ErrorHandler(error.responseJSON.errors);
                                });
                        },
                        function() {
                            toastr.warning('Konumunuzunu açmadan ziyareti tamamlayamazsınız.');
                        }, {
                            enableHighAccuracy: true
                        }
                    );
                });
            });
        })(jQuery);
    </script>
@endpush
