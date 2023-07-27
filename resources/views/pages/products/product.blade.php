@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="card-header pb-0 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Ürün') }}</h6>
            <a href="{{ url('urunler') }}" class="btn btn-primary btn-sm">Liste</a>
        </div>
        <div class="card-body pt-4 p-3">
            <form id="FormProduct">
                <input type="hidden" name="id" value="{{ $product?->id }}">
                <div class="form-group">
                    <label for="product-name" class="form-control-label">{{ __('Ürün Adı') }}</label>
                    <input class="form-control" value="{{ $product?->name }}" type="text" id="product-name" name="name">
                </div>
            </form>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <button type="button" onclick="window.location.reload();" class="btn bg-gradient-warning btn-md"><i class="fas fa-sync-alt"></i> {{ 'İptal Et' }}</button>
                <button type="button" id="submit" class="btn bg-gradient-dark btn-md"><i class="fas fa-check"></i> {{ 'Kaydet' }}</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("#submit").on('click', function() {
            RequestHandler.Request('urun/kaydet', '#FormProduct', null, function(response) {
                window.location.reload();
            },
            function(error) {
                RequestHandler.ErrorHandler(error.responseJSON.errors);
            });
        });
    </script>
@endpush