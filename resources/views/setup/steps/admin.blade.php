<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
@if (!$step['completed'])
    <div class="collapse {{ $active ? 'show' : null }}">
        <div class="card-body">
            <p>In order to get started, you'll need to create an Admin account</p>
            <form wire:submit.prevent="run(Object.fromEntries(new FormData($event.target)))">
                <div class="form-group">
                    <label for="company">Company/Workspace name</label>
                    <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}" required>
                    @error('company') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    @error('password') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary btn-md" type="submit" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Create Admin Account
                </button>
            </form>
        </div>
    </div>
@endif
