<div>
    @if($this->progress == 100)
        <div class="text-center">
            <h2 class="text-primary">Application Setup Complete</h2>
            <a href="{{ route('login') }}" class="btn btn-primary btn-md">Login</a>
        </div>
    @else
        <h2 class="text-center">Application Setup</h2>
    @endif
    <div class="text-center m-2 invisible" wire:loading.class.remove="invisible">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="progress mb-2" style="height: 8px">
        <div class="progress-bar" role="progressbar" style="width: {{ $this->progress }}%"></div>
    </div>
    <div class="accordion">
        @foreach ($steps as $index => $step)
            <div class="card">
                @include($step['view'], [
                    'step' => $step,
                    'active' => $index === $this->active,
                    'iteration' => $loop->iteration,
                    'total' => count($steps)
                ])
            </div>
        @endforeach
        </div>
    </div>
</div>
