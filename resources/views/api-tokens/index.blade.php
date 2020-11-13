@extends('sendportal::layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">

                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        {{ __('API Tokens') }}
                    </div>
                </div>

                @if ($tokens->isEmpty())

                    <div class="card-body">
                        <p>No API tokens have been generated.</p>
                    </div>

                @else

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Token</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->api_token }}</td>
                                <td>{{ $token->description }}</td>
                                <td>{{ $token->created_at }}</td>
                                <td>
                                    <form action="{{ route('api-tokens.destroy', $token->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-light delete-token">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @endif

            </div>

            <div class="card mt-3">
                <div class="card-header">
                    {{ __('Add New Token') }}
                </div>
                <div class="card-body">

                    <form action="{{ route('api-tokens.store') }}" method="post">

                        @csrf
                        <div class="form-group row">
                            <label for="token-description" class="col-sm-2">{{ __('Description') }}</label>

                            <div class="col-sm-6">
                                <input type="text" id="token-description" class="form-control" name="description" placeholder="Optional description for your API token…">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <input type="submit" class="btn btn-md btn-primary" value="{{ __('Add Token') }}">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
      let tokenDeleteButtons = document.getElementsByClassName('delete-token');

      Array.from(tokenDeleteButtons).forEach((element) => {
        element.addEventListener('click', (event) => {
          event.preventDefault();

          let confirmDelete = confirm('Are you sure you want to permanently delete this token?');

          if (confirmDelete) {
            element.closest('form').submit();
          }
        });
      });
    </script>

@endsection()
