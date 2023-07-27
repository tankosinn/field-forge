@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="card-header pb-0 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Yönetici') }}</h6>
            <a href="{{ url('yoneticiler') }}" class="btn btn-primary btn-sm">Liste</a>
        </div>
        <div class="card-body pt-4 p-3">
            <form id="FormAdmin">
                <input type="hidden" name="id" value="{{ $user?->id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user-name" class="form-control-label">{{ __('Ad Soyad') }}</label>
                            <input class="form-control" value="{{ $user?->name }}" type="text" id="user-name" name="name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user-email" class="form-control-label">{{ __('E-posta') }}</label>
                            <input class="form-control" value="{{ $user?->email }}" type="email" id="user-email" name="email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user.phone" class="form-control-label">{{ __('Telefon Numarası') }}</label>
                            <input class="form-control" type="tel" id="number" name="phone" value="{{ $user?->phone }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user.phone" class="form-control-label">{{ $user ? 'Yeni ' : '' }}Şifre</label>
                            <input class="form-control" type="password" id="password" name="password">
                        </div>
                    </div>
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
            RequestHandler.Request('yonetici/kaydet', '#FormAdmin', null, function(response) {
                window.location.reload();
            },
            function(error) {
                RequestHandler.ErrorHandler(error.responseJSON.errors);
            });
        });
    </script>
@endpush