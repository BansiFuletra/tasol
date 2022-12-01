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
    @include('layouts.navbars.auth.topnav', ['title' => 'Products'])
    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6>Product Lists</h6>
                            <a href="{{route('product.create')}}" class="btn btn-primary btn-sm ms-auto">Add Product</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table row-border align-items-center justify-content-center mb-0 display nowrap" id="myTable" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                    <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                                    <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Category</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $key => $product)
                                    <tr>
                                        <td><span class="text-xs font-weight-bold">{{$key+1}}</span></td>
                                        <td><span class="text-xs font-weight-bold">{{$product->name}}</span></td>
                                        <td><span class="text-xs font-weight-bold">{{$product->quantity}}</span></td>
                                        <td><span class="text-xs font-weight-bold">{{$product->price}}</span></td>
                                        <td><span class="text-xs font-weight-bold">{{$product->category_name}}</span></td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a href="{{route('product.edit', $product->id)}}" class="p-2"><p class="btn btn-xs btn-primary btn-flat">Edit</p></a>
                                                {{--<button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button>--}}
                                                <form method="POST" action="{{ route('product.destroy', $product->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    {{--<p class="text-sm font-weight-bold mb-0 show_confirm">Delete</p>--}}
                                                    <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button>
                                                </form>
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
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form =  $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
            title: `Are you sure you want to delete this product?`,
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
    });
</script>
@endpush