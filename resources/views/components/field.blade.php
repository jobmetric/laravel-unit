<label class="col-lg-4 col-form-label fw-semibold fs-6">{{ __('unit::base.field_name', ['field' => __('unit::base.fields.' . $type)]) }}</label>
<div class="col-lg-8 fv-row">
    <div class="input-group input-group-solid flex-nowrap">
        <input type="number" name="{{ $type }}" class="form-control form-control-lg form-control-solid" placeholder="{{ __('unit::base.field_enter', ['field' => __('unit::base.fields.' . $type)]) }}" value="{{ $dataInput }}">
        <div class="overflow-hidden flex-grow-1">
            <select name="{{ $type }}_class" class="form-select form-select-solid rounded-start-0 border-start" data-control="select2" data-placeholder="{{ __('unit::base.field_select', ['field' => __('unit::base.fields.' . $type)]) }}">
                <option></option>
                @foreach($units as $id => $name)
<option value="{{ $id }}"{{ $isSelected($id) ? ' selected' : '' }}>{{ $name }}</option>
                @endforeach

            </select>
        </div>
    </div>
</div>
