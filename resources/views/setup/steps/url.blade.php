<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        @if($step['completed'])
            - set to <a href="{{ config('app.url') }}" target="_blank">{{ config('app.url') }}</a>
        @endif
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
<div class="collapse {{ $active ? 'show' : null }}">
    <div class="card-body">
        @if ($step['completed'])
            <p>✔️ The Application url is set to {{ config('app.url') }}</p>
            <button class="btn btn-primary btn-md" wire:click="next">Next</button>
        @else
            <form wire:submit.prevent="run(Object.fromEntries(new FormData($event.target)))">
                <div class="form-group">
                    <label for="url">Application Url</label>
                    <input type="url" class="form-control" id="url" name="url" placeholder="https://sendportal.yourdomain.com" value="{{ old('url') }}" required>
                    @error('url') <span class="form-text text-danger">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary btn-md" type="submit">Save Application URL</button>
            </form>
        @endif
    </div>
</div>
