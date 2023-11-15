<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
            line-height: inherit;
        }

        body {
            page-break-before: always;
        }

        .header {
            margin-bottom: 1.5rem
        }

        .header tr th {
            line-height: 85%;
        }

        table {
            width: 100%;
            margin: 0;
            vertical-align: top;
            border-collapse: collapse;
        }

        table tr {
            border-collapse: collapse;
        }

        table td,
        table th {
            margin: 0;
            text-align: left;
            padding: 0;
            font-size: 12px;
        }

        table th {
            font-weight: bolder;
            color: #1e1e1e;
            background: #fff;
        }

        table td {
            font-weight: normal;
            color: #333;
        }

        table td:nth-child(even) {
            background: #f2f2ff;
        }

        .bg-secondary {
            background-color: #f2f2f2;
        }

        .bg-dark {
            background-color: #344767;
        }

        .bg-info {
            background-color: #a81c44;
        }

        .text-white {
            color: #fff;
        }

        .text-sm {
            font-size: 8px;
        }

        .font-weight-normal {
            font-weight: 400;
        }

        .font-weight-bolder {
            font-weight: bolder;
        }

        .padding {
            padding: 0.5rem 1rem;
        }

        .border {
            border: 1px solid #344767 !important;
        }

        .border-secondary {
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <th style="width: 25%; text-align:left;font-size:10px;font-weight:bold; vertical-align: middle;">
                <div class="font-weight-bolder">
                    Sistem Yetkilisi
                </div>
                <div class="font-weight-normal">
                    {{ Auth::user()->name }}
                </div>
            </th>
            <th style="width: 50%; text-align: center; font-size:16px;vertical-align: middle;">
                <span class="font-weight-normal" style="font-size: 14px;">Field Forge</span>
                <div>
                    Yerel Zincir
                </div>
            </th>
            <th style="width: 25%; text-align:right;font-size:10px;vertical-align: middle;">
                <div class="font-weight-bolder">
                    Düzenleme Tarihi
                </div>
                <div class="font-weight-normal">
                    {{ date('d.m.Y') }}
                </div>
            </th>
        </tr>
    </table>
    <table class="header">
        <tr>
            <td>
                <div class="font-weight-bolder text-sm" style="text-align: center;">
                    Satış Noktası:
                    {{ request()->store ? App\Models\Store::where('id', request()->store)->first()->name : 'Tümü' }}
                    |
                    Şube:
                    {{ request()->branch ? App\Models\StoreBranch::where('id', request()->branch)->first()->name : 'Tümü' }}
                    |
                    Personel:
                    {{ request()->employee ? App\Models\User::where('id', request()->employee)->first()->name : 'Tümü' }}
                    |
                    Tarih Aralığı:
                    {{ date_format(date_create(request()->from), 'd.m.Y') }} -
                    {{ date_format(date_create(request()->until), 'd.m.Y') }}
                </div>
            </td>
        </tr>
    </table>
    @php
        $prepareStores = request()->store ? App\Models\Store::where('id', request()->store)->get() : App\Models\Store::all();
    @endphp

    @foreach ($prepareStores as $store)
        <table>
            <tbody>
                <tr>
                    <th class="padding bg-secondary border-secondary">{{ $store->name }}</th>
                </tr>
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
                        
                        $hasVisits = $visits->exists();
                    @endphp
                    @if ($hasVisits || request()->type == 2)
                        <tr>
                            <th class="padding border-secondary">{{ $branch->name }}</th>
                        </tr>
                        @if ($hasVisits)
                            @foreach ($visits->get() as $visit)
                                <tr>
                                    <td class="bg-dark text-white padding border-secondary">
                                        <div class="font-weight-bolder">
                                            {{ $store->name }} -
                                            {{ $branch->name }}
                                        </div>
                                        {{ $visit->employee()->first()->name }}
                                        <br>
                                        {{ $visit->created_at->format('d.m.Y H.i') }}
                                        -
                                        {{ $visit->updated_at->format('H.i') }}

                                    </td>
                                </tr>
                                <tr>
                                    <th class="font-weight-bolder padding bg-dark text-white border-secondary">
                                        Stockouts
                                    </th>
                                </tr>
                                @foreach ($visit->stock_outs()->get() as $i => $stock_out)
                                    <tr>
                                        <td class="padding border-secondary {{ $i % 2 == 0 ? 'bg-secondary' : '' }}">
                                            {{ $stock_out->product()->first()->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td class="font-weight-bolder text-dark text-sm padding">
                                    Tamamlanan Ziyaret Kaydı Bulunamadı.
                                </td>
                            </tr>
                        @endif
                        </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
