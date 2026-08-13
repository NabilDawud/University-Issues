@extends('cms.parent')

@section('title', 'Show Category')

@section('main-title', 'Show Category')

@section('sub-title', 'show category')

@section('styles')
@endsection

@section('content')
    <!-- Custom validation -->
    <div class="col-lg-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <div class=" d-flex justify-between align-items-center">
                    <div class="card-title">Show Category</div>
                    <div>
                        @can('Index Category')
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to Categories</a>
                        @endcan
                        @can('Edit Category')
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-info">Edit
                                Category</a>
                        @endcan
                    </div>
                </div>
            </div>
            <form class="needs-validation" novalidate>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $category->title ?? '') }}" disabled />
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex align-items-center justify-between flex-wrap row-gap-2 column-gap-4">
                            <span class="font-bold text-body-secondary">Created at: <span
                                    class="font-medium">{{ $category->created_at->format('Y-m-d H:i:s A') }}</span></span>
                            <span class="font-bold text-body-secondary">Last Updated: <span
                                    class="font-medium">{{ $category->updated_at->format('Y-m-d H:i:s A') }}
                                    ({{ $category->updated_at->diffForHumans() }})</span></span>
                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
