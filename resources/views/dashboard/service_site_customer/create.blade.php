@extends('dashboard.layouts.app')

@section('pageTitle', 'Service Site & Customer Service')

@section('content')
<div class="container">
    <div class="card p-4">
        <h3>Create Service Site / Customer Service</h3>
        <form method="POST" action="{{ route('service-site-customer.store') }}" id="sscForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Editor One</label>
                <div id="editor-one" style="min-height:150px;background:#fff;border:1px solid #ddd"></div>
                <input type="hidden" name="editor_one" id="editor_one_input" />
            </div>

            <div class="mb-3">
                <label class="form-label">Editor Two</label>
                <div id="editor-two" style="min-height:150px;background:#fff;border:1px solid #ddd"></div>
                <input type="hidden" name="editor_two" id="editor_two_input" />
            </div>

            <div class="mb-3">
                <label class="form-label">Editor Three</label>
                <div id="editor-three" style="min-height:150px;background:#fff;border:1px solid #ddd"></div>
                <input type="hidden" name="editor_three" id="editor_three_input" />
            </div>

            <button class="btn btn-primary" type="submit">Save</button>
        </form>

        <hr />
        <h5>Existing Items</h5>
        <div id="itemsList">
            @foreach(App\Models\ServiceSiteAndCustomerService::latest()->limit(10)->get() as $it)
                <div class="p-2 mb-2 border d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $it->name }}</strong>
                        <div class="text-muted">{{ $it->phone }}</div>
                    </div>
                    <div>
                        <a href="{{ route('service-site-customer.edit', $it->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('service-site-customer.destroy', $it->id) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('js')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean']
            ];

            var quillOne = new Quill('#editor-one', { theme: 'snow', modules: { toolbar: toolbarOptions }});
            var quillTwo = new Quill('#editor-two', { theme: 'snow', modules: { toolbar: toolbarOptions }});
            var quillThree = new Quill('#editor-three', { theme: 'snow', modules: { toolbar: toolbarOptions }});

            document.getElementById('sscForm').addEventListener('submit', function(){
                document.getElementById('editor_one_input').value = quillOne.root.innerHTML;
                document.getElementById('editor_two_input').value = quillTwo.root.innerHTML;
                document.getElementById('editor_three_input').value = quillThree.root.innerHTML;
            });
        });
    </script>
@endpush
