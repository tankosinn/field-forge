@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="card-header pb-0 px-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Satış Temsilcisi') }}</h6>
            <a href="{{ url('satis-temsilcileri') }}" class="btn btn-primary btn-sm">Liste</a>
        </div>
        <div class="card-body pt-4 p-3">
            <form id="FormEmployee">
                <input type="hidden" name="id" value="{{ $user?->id }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="manager" class="form-control-label">{{ __('Yönetici') }}</label>
                            <select name="manager" id="manager" class="form-control select2">
                                <option value="">Yönetici Seçiniz..</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" {{$user?->manager == $manager->id ? 'selected' : '' }} >{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
    </div>
    <div class="card mt-3">
        <div class="card-body m-0">
            <div class="row">
                <div class="col-md-10">
                    <select id="store-branch" class="form-control select2">
                        <option value="">Satış Noktası Seçiniz</option>
                        @if ($stores)
                            @foreach ($stores as $store)
                                @if ($store->branches()->get())
                                    @foreach ($store->branches()->orderBy('name')->get() as $item)
                                        <option value="{{$item->id}}">{{$store->name}} - {{$item->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="javascript: void(0);" class="btn btn-success d-block no-animation" id="add-route"><i class="fas fa-plus"></i> Ruta Ekle</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link active" href="#day-0" data-bs-toggle="tab">Pazartesi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#day-1" data-bs-toggle="tab">Salı</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#day-2" data-bs-toggle="tab">Çarşamba</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#day-3" data-bs-toggle="tab">Perşembe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#day-4" data-bs-toggle="tab">Cuma</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#day-5" data-bs-toggle="tab">Cumartesi</a>
                </li>
              </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                @for ($i = 0; $i < 6; $i++)
                <div class="tab-pane fade in show {{ $i == 0 ? 'active' : '' }}" id="day-{{ $i }}">
                    <div class="dd">
                        <ol class="dd-list" id="routes-{{ $i }}">
                            @if ($user && $user->routes()->where('day', $i)->get())
                                @foreach ($user->routes()->where('day', $i)->orderBy('sort')->get() as $route)
                                    @php($store_branch = $route->store_branch()->first())
                                    <li class="dd-item dd-nodrag" data-id="{{ $store_branch->id }}" data-route="{{ $route->id }}">
                                        <div class="dd-handle dd-handle-drag"></div>
                                        <div class="dd-content">
                                            <div class="dd-handle-title">
                                                {{ $store_branch->name }}
                                                <div class="font-weight-light font-style-italic text-muted">{{ $route->updated_at->format('d.m.Y H:i:s') }}</div>
                                            </div>
                                            <div class="dd-list-tools">
                                                <a href="javascript: void(0);" class="text-danger remove-route">
                                                    <i class="fas fa-trash"></i> Sil
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ol>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <button type="button" onclick="window.location.reload();" class="btn bg-gradient-warning btn-md"><i class="fas fa-sync-alt"></i> {{ 'İptal Et' }}</button>
                <button type="button" id="submit" class="btn bg-gradient-dark btn-md"><i class="fas fa-check"></i> {{ 'Kaydet' }}</button>
            </div>
        </div>
    </div>
    <ul class="d-none">
        <li class="dd-item dd-nodrag" id="route">
            <div class="dd-handle dd-handle-drag"></div>
            <div class="dd-content">
                <div class="dd-handle-title">
                </div>
                <div class="dd-list-tools">
                    <a href="javascript: void(0);" class="text-warning" onclick="$.deleteRow(this, '#routes', function() {toastr.success('Rut iptal edildi.')}, true, null, 'list')">
                        <i class="fas fa-times"></i> İptal Et
                    </a>
                </div>
            </div>
        </li>
    </ul>
@endsection

@push('scripts')
    <script>
        (function($) {
            $(function() {
                $(".dd").nestable({
                    group: 1,
                    maxDepth: 1,
                });

                $(".dd").on('change', function() {
                    toastr.warning("Yaptığınız değişikliklerin uygulanması için kaydedin.");
                });
        
                $("#add-route").on('click', function() {
                    if($("#store-branch").val() !== "") {
                        if($(".tab-pane.active .dd-item[data-id='"+ $("#store-branch").val() +"']").length > 0) {
                            if($(".tab-pane.active .dd-item[data-id='"+ $("#store-branch").val() +"']").data('delete')) {
                                $(".tab-pane.active .dd-item[data-id='"+ $("#store-branch").val() +"']").show();
                                toastr.success('Satış noktası eklendi.');
                            } else {
                                toastr.warning($("#store-branch option:selected").html() + " bu ruta zaten eklendi. Farklı bir satış noktası seçiniz.");
                            }
                        } else {
                            $.addRow('#route', '.tab-pane.active .dd-list', false, function(element) {
                            $(element).attr('data-id', $("#store-branch").val());
                            $(element).find('.dd-handle-title').html($("#store-branch option:selected").html() + " <div class='font-weight-light text-danger'> Yeni Eklenen Rut</span> ");
                        }, true);
                            toastr.success('Satış noktası eklendi.');
                        }

                        $("#store-branch").val("").change();
                    } else {
                        toastr.warning("Satış noktası seçiniz.")
                    }
                });

                $(".remove-route").on('click', function() {
                    var route = $(this); 
                    $.confirm({
                        title: 'Rut Silinecek',
                        content: 'Rutu Silmek İstediğinize Emin Misiniz?',
                        theme: 'modern',
                        closeIcon: true,
                        autoClose: 'cancel|10000',
                        scrollToPreviousElement: false, // add this line 
                        scrollToPreviousElementAnimate: false, // add this line 
                        buttons: {
                            confirm: {
                                text: 'Evet, Rutu Sil',
                                btnClass: 'btn-red',
                                action: function (e) {
                                    $(route).closest('.dd-item').attr('data-delete', true).hide();
                                    $(".dd").change();
                                }
                            },
                            cancel: {
                                text: 'Hayır',
                                btnClass: 'btn-blue'
                            }
                        }
                    });
                });

                $("#submit").on("click", function () {
                    var routes = {};
                    $(".dd").each(function (index) {
                        routes[index] = $(this).data("nestable").serialize();
                        if (index + 1 === $(".dd").length) {
                            RequestHandler.Request('satis-temsilcisi/kaydet', '#FormEmployee', {routes}, function(response) {
                                window.location.reload();
                            },
                            function(error) {
                                RequestHandler.ErrorHandler(error.responseJSON.errors);
                            });
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endpush