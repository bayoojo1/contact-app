@extends('layouts.main')

@section('title', 'Contact App | All Contacts') {{-- Note that you don't need to add a closing section tag when you have a second argument in the section declaration --}}

@section('content')

<main class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header card-title">
                <div class="d-flex align-items-center">
                  <h2 class="mb-0">All Contacts</h2>
                  <div class="ml-auto">
                    <a href="{{ route('contacts.create') }}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add New</a>
                  </div>
                </div>
              </div>
            <div class="card-body">
              @includeIf('contacts._filter')
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($contacts as $index => $contact)
                    @include('contacts._contact', ['contact' => $contact, 'index' => $index])
                  @empty
                    @include('contacts._empty')
                  @endforelse
                    {{-- @each('contacts._contact', $contacts, 'contact', 'contacts._empty') --}}
                </tbody>
              </table> 

              {{-- <nav class="mt-4">
                  <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                      <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#">Next</a>
                    </li>
                  </ul>
                </nav> --}}
                {{ $contacts->withQueryString()->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection