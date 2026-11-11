@props(['filters' => [], 'title' => 'Filter', 'hiddens' => []])

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg px-4">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body rounded-4">
                <form action="{{ url()->current() }}" method="GET" id="filterForm">
                    @foreach ($filters as $field => $filter)
                        @php
                            $type = $filter['type'] ?? 'text';
                            $id = $filter['id'] ?? $field;
                            $label = $filter['label'] ?? ucfirst(str_replace('_', ' ', $field));
                        @endphp

                        <div class="mb-3">
                            @if ($type !== 'checkbox' && $type !== 'radio')
                                <label for="{{ $id }}" class="form-label fw-semibold">
                                    {{ $filter['label'] ?? $label }}
                                </label>
                            @endif

                            @if ($type === 'select')
                                <select class="form-select" name="{{ $field }}" id="{{ $id }}"
                                    @isset($filter['multiple']) multiple @endisset>
                                    @if(isset($filter['has_default_label']) && $filter['has_default_label'])
                                        <option value="">{{ $filter['default_label'] ?? 'Pilih ' . $label }}</option>
                                    @endif
                                    @isset($filter['options'])
                                        @foreach ($filter['options'] as $optionLabel => $value)
                                            <option value="{{ $value }}"
                                                {{ (request()->query($field) ?? $filter['value']) == $value ? 'selected' : '' }}>
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>

                            @elseif ($type === 'checkbox' || $type === 'radio')
                                <div class="form-check">
                                    <input class="form-check-input" type="{{ $type }}"
                                        name="{{ $field }}" id="{{ $id }}"
                                        value="{{ $filter['value'] ?? '1' }}"
                                        @isset($filter['checked'])
                                            {{ $filter['checked'] ? 'checked' : '' }}
                                        @endisset>
                                    <label class="form-check-label" for="{{ $id }}">
                                        {{ $filter['label'] ?? $label }}
                                    </label>
                                </div>

                            @elseif ($type === 'range' || ($type === 'number' && isset($filter['min']) && isset($filter['max'])))
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" class="form-control" name="{{ $field }}_min"
                                            id="{{ $id }}_min" placeholder="Min"
                                            min="{{ $filter['min'] ?? '' }}" max="{{ $filter['max'] ?? '' }}"
                                            value="{{ $filter['value_min'] ?? '' }}">
                                    </div>
                                    <div class="col-auto">
                                        <span class="form-control-plaintext">—</span>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" name="{{ $field }}_max"
                                            id="{{ $id }}_max" placeholder="Max"
                                            min="{{ $filter['min'] ?? '' }}" max="{{ $filter['max'] ?? '' }}"
                                            value="{{ $filter['value_max'] ?? '' }}">
                                    </div>
                                </div>

                            @elseif ($type === 'date' || $type === 'datetime-local' || $type === 'time')
                                <input type="{{ $type }}" class="form-control" name="{{ $field }}"
                                    id="{{ $id }}" value="{{ $filter['value'] ?? '' }}"
                                    @isset($filter['min']) min="{{ $filter['min'] }}" @endisset
                                    @isset($filter['max']) max="{{ $filter['max'] }}" @endisset>

                            @elseif ($type === 'textarea')
                                <textarea class="form-control" name="{{ $field }}" id="{{ $id }}" rows="{{ $filter['rows'] ?? 3 }}"
                                    placeholder="{{ $filter['placeholder'] ?? 'Masukkan ' . $label }}">{{ $filter['value'] ?? '' }}</textarea>

                            @else
                                <input type="{{ $type }}" class="form-control" name="{{ $field }}"
                                    id="{{ $id }}"
                                    placeholder="{{ $filter['placeholder'] ?? 'Masukkan ' . $label }}"
                                    value="{{ $filter['value'] ?? '' }}"
                                    @isset($filter['min']) min="{{ $filter['min'] }}" @endisset
                                    @isset($filter['max']) max="{{ $filter['max'] }}" @endisset
                                    @isset($filter['step']) step="{{ $filter['step'] }}" @endisset>
                            @endif

                            @isset($filter['description'])
                                <div class="form-text text-muted small">
                                    {{ $filter['description'] }}
                                </div>
                            @endisset
                        </div>
                    @endforeach

                    @foreach ($hiddens as $field => $hidden)
                        <input type="hidden" name="{{ $field }}" id="{{ $hidden }}" value="{{ request()->query($field) }}">
                    @endforeach
                </form>
            </div>

            <div class="modal-footer">
                <div class="w-100 d-flex justify-content-end gap-2">
                    <a href="{{ url()->current() }}" class="btn btn-outline-primary rounded-2">
                        Reset
                    </a>
                    <button type="submit" form="filterForm" class="btn btn-primary rounded-2">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>