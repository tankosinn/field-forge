@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="card-header pb-0 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Satış Noktası') }}</h6>
            <a href="{{ url('satis-noktalari') }}" class="btn btn-primary btn-sm">Liste</a>
        </div>
        <div class="card-body pt-4 p-3">
            <form class="Form">
                <input type="hidden" name="id" value="{{ $store?->id }}">
                <div class="form-group">
                    <label for="store-name" class="form-control-label">{{ __('Satış Noktası Adı') }}</label>
                    <input class="form-control" value="{{ $store?->name }}" type="text" id="store-name" name="name">
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <form class="Form">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <tbody id="branches">
                        @if ($store && $store->branches()->get())
                            @foreach ($store->branches()->get() as $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="update_branch[id][]" value="{{ $item->id }}">
                                        <div class="form-group">
                                            <label>Şube Ad</label>
                                            <input type="text" class="form-control" name="update_branch[name][]" value="{{ $item->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Şube Adres</label>
                                            <textarea name="update_branch[address][]" class="form-control" rows="5">{{ $item->address }}</textarea>
                                        </div>
                                        <div class="btn btn-light disabled d-block no-animation">
                                            <i class="fas fa-lock"></i>
                                            Sadece Düzenleme Yapılabilir
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <a href="javascript: void(0);" onclick="$.addRow('#clone', '#branches')" class="btn btn-info d-block no-animation"><i class="fas fa-plus"></i> Yeni Şube</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <button type="button" onclick="window.location.reload();" class="btn bg-gradient-warning btn-md"><i class="fas fa-sync-alt"></i> {{ 'İptal Et' }}</button>
                <button type="button" id="submit" class="btn bg-gradient-dark btn-md"><i class="fas fa-check"></i> {{ 'Kaydet' }}</button>
            </div>
        </div>
    </div>
    <table class="d-none">
        <tbody>
        <tr id="clone">
            <td>
                <div class="form-group">
                    <label>Şube Ad</label>
                    <input type="text" class="form-control" name="branch[name][]">
                </div>
                <div class="form-group">
                    <label>Şube Adres</label>
                    <textarea name="branch[address][]" class="form-control" rows="5"></textarea>
                </div>
                <a href="javascript:void(0)" class="btn btn-warning d-block no-animation"
                   onclick="$.deleteRow(this, '#branches')">
                    <i class="fas fa-times"></i>
                    İptal Et
                </a>
            </td>
        </tr>
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        (function($) {
            $(function() {
                if ($("#branches tr").length === 0) {
                    $.addRow('#clone', '#branches');
                }

                $("#submit").on('click', function() {
                    RequestHandler.Request('satis-noktasi/kaydet', '.Form', null, function(response) {
                        window.location.reload();
                    },
                    function(error) {
                        RequestHandler.ErrorHandler(error.responseJSON.errors);
                    });
                });
            });
        })(jQuery);
    </script>
@endpush