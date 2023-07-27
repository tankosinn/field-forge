@extends('layouts.user_type.auth')

@section('content')
    <h4 class="m-0 font-weight-bolder"><i class="fas fa-bolt"></i> Raporlama</h4>
    <div class="row">
        <a target="_blank"
            href="{{ url('rapor/satis-noktalari?generated=true&from=' . date('Y-m-d') . '&until=' . date('Y-m-d') . '&type=1') }}"
            class="col-lg-6 mt-4">
            <div class="card h-100">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100"
                    style="background-image: url('/assets/img/curved-images/curved6.jpg');">
                    <span class="mask bg-gradient-danger"></span>
                    <div class="card-body position-relative z-index-1 h-100 p-3">
                        <h6 class="text-white font-weight-bolder">Gün Sonu Raporu</h6>
                        <p class="text-white m-0">
                            Gün sonu yapılan ziyaretleri raporlayabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ url('rapor/olustur/satis-noktalari') }}" class="col-lg-6 mt-4">
            <div class="card h-100">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100"
                    style="background-image: url('/assets/img/curved-images/curved8.jpg');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="card-body position-relative z-index-1 h-100 p-3">
                        <h6 class="text-white font-weight-bolder">Satış Noktaları Ziyaret Raporu</h6>
                        <p class="text-white m-0">
                            Satış noktalarına yapılan ziyaretleri filtreleyebilir, raporlayabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endsection
