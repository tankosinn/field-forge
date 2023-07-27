@extends('layouts.user_type.auth')

@section('content')
<div class="alert alert-white d-flex justify-content-between align-items-center" role="alert">
    <span class="text-dark">
       Satış Noktası Listesi
    </span>
    <a href="{{ url('satis-noktasi/ekle') }}" class="btn btn-primary btn-sm">Yeni Oluştur</a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0 table-striped">
                    <thead>
                        <tr>
                            <th class="text-secondary text-xxs opacity-7">
                                No
                            </th>
                            <th class="text-center text-secondary text-xxs opacity-8">
                                Satış Noktası
                            </th>
                            <th class="text-center text-secondary text-xxs opacity-8">
                                Kayıt Tarih/Saat
                            </th>
                            <th class="text-center text-secondary text-xxs opacity-8">
                                Son Güncelleme Tarih/Saat
                            </th>
                            <th class="text-center text-secondary text-xxs opacity-8">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $index => $store)
                        <tr>
                            <td class="ps-4">
                                <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">{{ $store->name }}</p>
                            </td>
                            <td class="text-center">
                                <span class="text-secondary text-xs font-weight-bold">{{ $store->created_at->format('d.m.Y H:i:s') }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-secondary text-xs font-weight-bold">{{ $store->updated_at->format('d.m.Y H:i:s') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ url('satis-noktasi/' . $store->slug) }}" class="btn btn-light btn-xs">
                                        <i class="fas fa-edit fa-sm"></i> Düzenle
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection