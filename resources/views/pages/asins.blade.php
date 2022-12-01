@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('style')
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            /*border: 1px solid #40b0fb!important;*/
            /*border-radius: 50%;*/

        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{
            background: linear-gradient(to bottom, rgb(64 176 251 / 39%) 0%, rgb(64 176 251 / 77%) 100%)!important;
            border-radius: 50%!important;
        }

    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Asin Details'])
    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Projects table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table row-border align-items-center justify-content-center mb-0 display nowrap" id="myTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asin</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amazon Prime Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amazon Prime Price Lowest</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Amazon Prime Inventory</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Lowest Buy Box Price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Is Lowest Price Prime</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Seller Id</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Seller Rating</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Handling Time(hours)</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Shipping Price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Number of Seller</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asinDetails as $key => $asin)
                                        <tr>
                                            <td><span class="text-xs font-weight-bold">{{$key+1}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->asin}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{($asin->currency != null) ? \App\Models\Utility::formatCurrency($asin->prime_price,$asin->currency) : $asin->prime_price}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{($asin->currency!=null) ? \App\Models\Utility::formatCurrency($asin->prime_price_lowest,$asin->currency) : $asin->prime_price_lowest}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->prime_inventory ?? NULL}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{($asin->currency != null) ? \App\Models\Utility::formatCurrency($asin->lowest_buy_box_price,$asin->currency) : $asin->lowest_buy_box_price}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->lowest_price_is_prime}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->seller_name}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->seller_rating}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->handling_time}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{($asin->currency != null) ? \App\Models\Utility::formatCurrency($asin->shipping_price,$asin->currency) : $asin->shipping_price}}</span></td>
                                            <td><span class="text-xs font-weight-bold">{{$asin->number_of_seller}}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#myTable').DataTable({
                "language" : {
                    "paginate": {
                        "previous": "«",
                        "next": "»"
                    }
                }
            });
        });
    </script>
@endpush