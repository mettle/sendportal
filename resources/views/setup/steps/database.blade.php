<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
<div class="collapse {{ $active ? 'show' : null }}">
    <div class="card-body">
        @if ($step['completed'])
            <p>✔️ Database connection successful.</p>
            <button class="btn btn-primary btn-md" wire:click="next">Next</button>
        @else
            @php
                $default = config('database.default', 'mysql');
            @endphp
            <p>✖️ A database connection could not be established. Please update your configuration and try again.</p>

            <form wire:submit.prevent="run(Object.fromEntries(new FormData($event.target)))">
                <div class="form-group">
                    <label for="connection">Database Connection</label>
                    <select name="connection" class="form-control" required>
                        <option value="">Please select ...</option>
                        @foreach(config('database.connections') as $key => $connection)
                            <option value="{{ $key }}" {{ $default === $key ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                    @error('connection') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="host">Database Host</label>
                    <input type="text" class="form-control" id="host" name="host" value="{{ old('host') ?? config("database.connections.{$default}.host", '127.0.0.1') }}" required>
                    @error('host') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="port">Database Port</label>
                    <input type="text" class="form-control" id="port" name="port" value="{{ old('port') ?? config("database.connections.{$default}.port", 3306) }}" required>
                    @error('port') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="database">Database Name</label>
                    <input type="text" class="form-control" id="database" name="database" value="{{ old('database') ?? config("database.connections.{$default}.database") }}" required>
                    @error('database') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="username">Database Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') ?? config("database.connections.{$default}.username") }}" required>
                    @error('username') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password">Database Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    @error('password') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary btn-md" type="submit" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Update Configuration
                </button>
            </form>
        @endif
    </div>
</div>
