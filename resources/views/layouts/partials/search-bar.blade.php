{{-- Reusable search bar for index pages --}}
<form method="GET" class="mb-3 no-print">
    <div class="input-group input-group-sm" style="max-width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="{{ $placeholder ?? 'Search...' }}" value="{{ request('search') }}" aria-label="Search">
        @if(isset($statusField))
        <select name="{{ $statusField }}" class="form-control" style="max-width:140px;" aria-label="Filter by status">
            <option value="">All Status</option>
            @foreach($statuses ?? ['active', 'inactive'] as $s)
                <option value="{{ $s }}" {{ request($statusField) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @endif
        <div class="input-group-append">
            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            @if(request('search') || request($statusField ?? '_'))
                <a href="{{ url()->current() }}" class="btn btn-default" title="Clear"><i class="fas fa-times"></i></a>
            @endif
        </div>
    </div>
</form>
