@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Product'])
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <form role="form" method="POST" action="{{ route('product.update',$product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Product</p>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{--<p class="text-uppercase text-sm">User Information</p>--}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name</label>
                                    <div class="col-md-12">
                                        <input class="form-control" type="text" value="{{ $product->name }}" name="name" id="name" placeholder="Add Product Name" required/>
                                        <div class="d-none" id="error-file-msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quantity" class="form-control-label">Quantity</label>

                                    <div class="col-md-12">
                                        <input class="form-control" type="number"  value="{{ $product->quantity }}" name="quantity" id="quantity" placeholder="Quantity" required/>
                                        <div class="d-none" id="error-file-msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price</label>

                                    <div class="col-md-12">
                                        <input class="form-control" type="text"  value="{{ $product->price }}" name="price" id="price" placeholder="Price" required/>
                                        <div class="d-none" id="error-file-msg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-control-label">Category</label>

                                    <div class="col-md-12">
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="">--Select Category--</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}" {{($product->category_id == $category->id) ? 'selected': ''}}>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="d-none" id="error-file-msg"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')

@endpush
