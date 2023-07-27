@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0"><i class="fas fa-store" aria-hidden="true"></i> Satış Noktaları Ziyaret Raporu</h6>
        </div>
        <div class="card-body py-0">
            <form id="Generate">
                <input type="hidden" name="generated" value="true">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="type" class="form-control-label">Durum</label>
                            <select name="type" id="type" class="form-control select2">
                                <option value="1" {{ request()->type == 1 ? 'selected' : 1 }}>Ziyaret Edilenler
                                </option>
                                <option value="2" {{ request()->type == 2 ? 'selected' : 2 }}>Tümü</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="store" class="form-control-label">Satış Noktası</label>
                            <select name="store" id="stores" class="form-control select2">
                                <option value="">Tümü</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}"
                                        {{ isset(request()->store) && request()->store == $store->id ? 'selected' : '' }}
                                        data-branches="{{ json_encode($store->branches()->get()) }}">
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="branch" class="form-control-label">Şube</label>
                            <select name="branch" id="branches" class="form-control select2">
                                <option value="">Öncelikle Satış Noktası Seçiniz</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee" class="form-control-label">Personel</label>
                            <select name="employee" class="form-control select2">
                                <option value="">Tümü</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ isset(request()->employee) && request()->employee == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="store" class="form-control-label">Tarih Aralığı</label>
                            <div class="input-group">
                                <input type="date" name="from"
                                    value="{{ isset(request()->from) ? request()->from : date('Y-m-d') }}"
                                    class="form-control">
                                <span class="input-group-text text-xs font-weight-bolder px-3">ila</span>
                                <input type="date" name="until"
                                    value="{{ isset(request()->until) ? request()->until : date('Y-m-d') }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <button type="button" onclick="window.location.href = window.location.href.split('?')[0]"
                    class="btn bg-gradient-warning btn-md">
                    <i class="fas fa-sync-alt"></i> İptal Et
                </button>
                <button type="button" id="submit" class="btn bg-gradient-dark btn-md">
                    <i class="fas fa-bolt"></i>
                    Oluştur
                </button>
            </div>
        </div>
    </div>
    @if ($generated)
        <div class="card mt-4">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="m-0"><i class="fas fa-bolt"></i> Rapor Oluşturuldu</h6>
                    <a href="{{ url('rapor/satis-noktalari?' . explode('?', url()->full())[1]) }}" target="_blank"
                        class="btn btn-sm btn-warning" id="print"><i class="fas fa-print"></i> Yazdır</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="report-table">
                    @php
                        $prepareStores = request()->store ? App\Models\Store::where('id', request()->store)->get() : $stores;
                    @endphp

                    @foreach ($prepareStores as $store)
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ $store->name }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $branches = $store->branches();
                                        if (request()->branch) {
                                            $branches->where('id', request()->branch);
                                        }
                                    @endphp
                                    @foreach ($branches->get() as $branch)
                                        @php
                                            $visits = $branch
                                                ->visits()
                                                ->where('status', 2)
                                                ->where('created_at', '>=', request()->from)
                                                ->where('created_at', '<=', request()->until);
                                            
                                            if (request()->employee) {
                                                $visits->where('employee', request()->employee);
                                            }
                                            
                                            $hasVisits = $visits->first();
                                        @endphp
                                        @if (request()->type == 2 || $hasVisits)
                                            <tr>
                                                <td>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>{{ $branch->name }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($hasVisits)
                                                                @foreach ($visits->get() as $visit)
                                                                    <tr>
                                                                        <td>
                                                                            <table>
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            class="bg-dark text-white font-weight-normal">
                                                                                            <div class="font-weight-bolder">
                                                                                                {{ $store->name }} -
                                                                                                {{ $branch->name }}
                                                                                            </div>
                                                                                            {{ $visit->employee()->first()->name }}
                                                                                            <br>
                                                                                            {{ $visit->created_at->format('d.m.Y H.i') }}
                                                                                            -
                                                                                            {{ $visit->updated_at->format('H.i') }}
                                                                                            <br>
                                                                                            <a href="{{ url('ziyaret/' . $visit->id . '/goruntule') }}"
                                                                                                target="_blank"
                                                                                                class="text-sm text-light">
                                                                                                <i class="fa fa-xs fa-external-link"
                                                                                                    aria-hidden="true"></i>
                                                                                                Görüntüle
                                                                                            </a>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <th class="font-weight-bolder">
                                                                                            Stockouts
                                                                                        </th>
                                                                                    </tr>
                                                                                    @foreach ($visit->stock_outs()->get() as $stock_out)
                                                                                        <tr>
                                                                                            <td>
                                                                                                {{ $stock_out->product()->first()->name }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td class="font-weight-bolder text-dark text-sm">
                                                                        Tamamlanan Ziyaret Kaydı Bulunamadı.
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        (function($) {
            $(function() {
                $("#stores").on('change', function() {
                    $("#branches").find('option').remove().trigger('change');
                    if ($(this).val() !== "") {
                        var branches = JSON.parse(JSON.stringify($(this).find('option:selected').data(
                            'branches')));

                        var option = new Option('Tümü', '');

                        $("#branches").append(option).trigger('change');

                        for (var i = 0; i < branches.length; i++) {
                            var option = new Option(branches[i].name, branches[i].id);
                            $("#branches").append(option).trigger('change');
                        }
                    } else {
                        var option = new Option('Öncelikle Satış Noktası Seçiniz', '');
                        $("#branches").append(option).trigger('change');
                    }
                });

                $("#stores").trigger('change');

                var branch = RequestHandler.GetParams(window.location.href)['branch'];

                if (typeof branch !== "undefined" && branch.trim() !== "") {
                    $("#branches").val(branch).trigger('change');
                }

                $("#submit").on('click', function() {
                    RequestHandler.Filter("#Generate");
                });
            });
        })(jQuery);
    </script>
@endpush
