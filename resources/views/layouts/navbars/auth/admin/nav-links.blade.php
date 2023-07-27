<li class="nav-item">
    <a class="nav-link {{ Request::is('anasayfa') || Request::is('/') ? 'active' : '' }}" href="{{ url('anasayfa') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-home ps-2 pe-2 text-center text-dark" aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Anasayfa</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('yoneticiler') ? 'active' : '' }}" href="{{ url('yoneticiler') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-briefcase ps-2 pe-2 text-center text-dark"
            aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Yöneticiler</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('urunler') ? 'active' : '' }}" href="{{ url('urunler') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-cube ps-2 pe-2 text-center text-dark" aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Ürünler</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('satis-noktalari') ? 'active' : '' }}" href="{{ url('satis-noktalari') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-store ps-2 pe-2 text-center text-dark" aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Satış Noktaları</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('satis-temsilcileri') ? 'active' : '' }}" href="{{ url('satis-temsilcileri') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-users ps-2 pe-2 text-center text-dark" aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Satış Temsilcileri</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ Request::is('rapor/olustur') ? 'active' : '' }}" href="{{ url('rapor/olustur') }}">
        <i style="font-size: 1rem;" class="fas fa-lg fa-bolt ps-2 pe-2 text-center text-dark" aria-hidden="true"></i>
        <span class="nav-link-text ms-1">Rapor Oluştur</span>
    </a>
</li>
